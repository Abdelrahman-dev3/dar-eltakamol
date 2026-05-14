<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Contributor;
use App\Models\ContributorDocument;
use App\Models\ContributorMovement;
use App\Models\Modification;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ContributorsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $contributors = Contributor::with(['departments.parent', 'managedCompanies'])->orderBy('created_at', 'desc')->paginate(15);

        return view('contributors.index', compact('contributors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        [$companies, $departments] = $this->membershipFormData();
        return view('contributors.create', compact('companies', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'id_number' => 'required|string|max:10|unique:contributors',
            'login_email' => 'required|string|email|max:255|unique:users,email',
            'phone_num' => 'nullable|string|max:15',
            'temp_password' => 'required|string|min:8|max:10',
            'iban' => 'nullable|string|max:24',
            'bank_name' => 'nullable|string|max:15',
            'position' => 'nullable|string|max:100',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'share_count_cr' => 'nullable|numeric|min:0',
            'is_board_member' => 'boolean',
            'committee_memberships' => 'nullable|array',
            'committee_memberships.*' => ['string', Rule::in(Contributor::committeeMembershipOptions())],
            'company_id' => 'nullable|exists:categories,id',
            'department_ids' => 'nullable|array',
            'department_ids.*' => 'exists:categories,id',
            'managed_company_ids' => 'nullable|array',
            'managed_company_ids.*' => 'exists:categories,id',
            'documents.*' => 'nullable|file|max:10240',
        ]);

        $validated['committee_memberships'] = $this->normalizeCommitteeMemberships(
            $validated['committee_memberships'] ?? []
        );
        $validated['is_board_member'] = (bool) ($validated['is_board_member'] ?? false);

        $departmentIds = $this->validateContributorDepartments(
            $validated['company_id'] ?? null,
            $validated['department_ids'] ?? []
        );
        $managedCompanyIds = $this->validateManagedCompanies($validated['managed_company_ids'] ?? []);

        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('contributors/profiles', $fileName, 'public');
            $validated['profile_picture'] = $filePath;
        }

        $loginEmail = $validated['login_email'];
        unset($validated['company_id'], $validated['department_ids'], $validated['managed_company_ids'], $validated['login_email']);

        $contributor = DB::transaction(function () use ($validated, $departmentIds, $managedCompanyIds, $loginEmail) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $loginEmail,
                'phone' => $validated['phone_num'] ?? null,
                'id_number' => $validated['id_number'],
                'password' => Hash::make($validated['temp_password']),
            ]);

            $validated['user_id'] = $user->id;

            $contributor = Contributor::create($validated);
            $contributor->departments()->sync($departmentIds);
            $contributor->managedCompanies()->sync($managedCompanyIds);
            $this->syncLinkedUserDepartments($contributor, $departmentIds);

            return $contributor;
        });

        if ($request->hasFile('documents')) {
            $this->uploadDocuments($contributor, $request->file('documents'), $request->input('document_descriptions', []));
        }

        return redirect()->route('contributors.index')
            ->with('success', 'تم إضافة المساهم بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contributor $contributor): View
    {
        $contributor->load([
            'user',
            'sellShares',
            'sharesPOs',
            'shareTransLines',
            'userProfits',
            'documents.uploader',
            'departments.parent',
            'managedCompanies',
        ]);

        return view('contributors.show', compact('contributor'));
    }

    public function statement(Contributor $contributor): View
    {
        $contributor->load([
            'user',
            'departments.parent',
        ]);

        $currentShares = (float) ($contributor->share_count_cr ?? 0);
        $totalShares = (float) Contributor::query()->sum('share_count_cr');
        $ownershipPercentage = $totalShares > 0 ? round(($currentShares / $totalShares) * 100, 4) : 0;
        $currentSharePrice = (float) Setting::getValue('base_price', 0);

        $sellShares = $contributor->sellShares()
            ->with(['sharesPOs.contributor'])
            ->latest('insert_date')
            ->latest()
            ->get();

        $purchaseOrders = $contributor->sharesPOs()
            ->with(['sellShare.seller'])
            ->latest('insert_date')
            ->latest()
            ->get();

        $movements = ContributorMovement::query()
            ->with(['fromContributor', 'toContributor', 'sharesTrans'])
            ->where(function ($query) use ($contributor): void {
                $query
                    ->where('from_contributor_id', $contributor->id)
                    ->orWhere('to_contributor_id', $contributor->id);
            })
            ->latest('date')
            ->latest()
            ->get();

        $shareTransLines = $contributor->shareTransLines()
            ->with('sharesTrans')
            ->latest()
            ->get();

        return view('contributors.statement', compact(
            'contributor',
            'currentShares',
            'totalShares',
            'ownershipPercentage',
            'currentSharePrice',
            'sellShares',
            'purchaseOrders',
            'movements',
            'shareTransLines'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contributor $contributor): View
    {
        $contributor->load('departments.parent', 'managedCompanies', 'user');
        [$companies, $departments] = $this->membershipFormData();

        return view('contributors.edit', compact('contributor', 'companies', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contributor $contributor): RedirectResponse
    {
        $contributor->loadMissing('user');

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'id_number' => 'required|string|max:10|unique:contributors,id_number,' . $contributor->id,
            'login_email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($contributor->user?->id),
            ],
            'phone_num' => 'nullable|string|max:15',
            'temp_password' => [
                Rule::requiredIf(!$contributor->user),
                'nullable',
                'string',
                'min:8',
                'max:10',
            ],
            'iban' => 'nullable|string|max:24',
            'bank_name' => 'nullable|string|max:15',
            'position' => 'nullable|string|max:100',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'share_count_cr' => 'nullable|numeric|min:0',
            'is_board_member' => 'boolean',
            'committee_memberships' => 'nullable|array',
            'committee_memberships.*' => ['string', Rule::in(Contributor::committeeMembershipOptions())],
            'company_id' => 'nullable|exists:categories,id',
            'department_ids' => 'nullable|array',
            'department_ids.*' => 'exists:categories,id',
            'managed_company_ids' => 'nullable|array',
            'managed_company_ids.*' => 'exists:categories,id',
            'line_notes' => 'required|string',
            'documents.*' => 'nullable|file|max:10240',
        ]);

        $validated['committee_memberships'] = $this->normalizeCommitteeMemberships(
            $validated['committee_memberships'] ?? []
        );
        $validated['is_board_member'] = (bool) ($validated['is_board_member'] ?? false);

        $departmentIds = $this->validateContributorDepartments(
            $validated['company_id'] ?? null,
            $validated['department_ids'] ?? []
        );
        $managedCompanyIds = $this->validateManagedCompanies($validated['managed_company_ids'] ?? []);

        if ($request->hasFile('profile_picture')) {
            if ($contributor->profile_picture && Storage::disk('public')->exists($contributor->profile_picture)) {
                Storage::disk('public')->delete($contributor->profile_picture);
            }

            $file = $request->file('profile_picture');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('contributors/profiles', $fileName, 'public');
            $validated['profile_picture'] = $filePath;
        }

        $lineNotes = $validated['line_notes'];
        $loginEmail = $validated['login_email'];
        $plainPassword = $validated['temp_password'] ?? null;

        unset($validated['company_id'], $validated['department_ids'], $validated['managed_company_ids'], $validated['line_notes'], $validated['login_email']);

        DB::transaction(function () use ($contributor, $validated, $departmentIds, $managedCompanyIds, $loginEmail, $plainPassword): void {
            $user = $this->syncContributorUser($contributor, $validated, $loginEmail, $plainPassword);
            $validated['user_id'] = $user->id;

            $contributor->update($validated);
            $contributor->departments()->sync($departmentIds);
            $contributor->managedCompanies()->sync($managedCompanyIds);
            $this->syncLinkedUserDepartments($contributor, $departmentIds);
        });

        $url = url()->previous();
        Modification::logChange($url, $lineNotes, auth()->user()->id);

        if ($request->hasFile('documents')) {
            $this->uploadDocuments($contributor, $request->file('documents'), $request->input('document_descriptions', []));
        }

        return redirect()->route('contributors.index')
            ->with('success', 'تم تحديث بيانات المساهم بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contributor $contributor): RedirectResponse
    {
        $contributor->delete();

        return redirect()->route('contributors.index')
            ->with('success', 'تم حذف المساهم بنجاح');
    }

    /**
     * Upload documents for a contributor.
     */
    private function uploadDocuments(Contributor $contributor, array $files, array $descriptions = []): void
    {
        foreach ($files as $index => $file) {
            if ($file->isValid()) {
                $mimeType = $file->getMimeType();
                $fileType = str_starts_with($mimeType, 'image/') ? 'image' : 'document';

                $fileName = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('contributors/documents', $fileName, 'public');

                ContributorDocument::create([
                    'contributor_id' => $contributor->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'file_type' => $fileType,
                    'file_size' => $file->getSize(),
                    'mime_type' => $mimeType,
                    'description' => $descriptions[$index] ?? null,
                    'uploaded_by' => Auth::id(),
                ]);
            }
        }
    }

    /**
     * Download a contributor document.
     */
    public function downloadDocument(ContributorDocument $document)
    {
        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }

    /**
     * Delete a contributor document.
     */
    public function deleteDocument(ContributorDocument $document): RedirectResponse
    {
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $contributorId = $document->contributor_id;
        $document->delete();

        return redirect()->route('contributors.show', $contributorId)
            ->with('success', 'تم حذف الملف بنجاح');
    }

    private function membershipFormData(): array
    {
        $companies = Category::companies()->orderBy('name')->get();
        $departments = Category::departments()->with('parent')->orderBy('name')->get();

        return [$companies, $departments];
    }

    private function normalizeCommitteeMemberships(array $committeeMemberships): array
    {
        return collect($committeeMemberships)
            ->filter()
            ->map(fn ($membership) => trim((string) $membership))
            ->filter(fn ($membership) => in_array($membership, Contributor::committeeMembershipOptions(), true))
            ->unique()
            ->values()
            ->all();
    }

    private function validateContributorDepartments(?int $companyId, array $departmentIds): array
    {
        $departmentIds = collect($departmentIds)
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        if ($companyId) {
            $company = Category::findOrFail($companyId);

            if (!$company->isCompany()) {
                throw ValidationException::withMessages([
                    'company_id' => 'يرجى اختيار شركة صحيحة.',
                ]);
            }
        }

        if (empty($departmentIds)) {
            if ($companyId) {
                throw ValidationException::withMessages([
                    'department_ids' => 'اختر إدارة واحدة على الأقل داخل الشركة المحددة.',
                ]);
            }

            return [];
        }

        $departments = Category::with('parent')
            ->whereIn('id', $departmentIds)
            ->get();

        if ($departments->count() !== count($departmentIds)) {
            throw ValidationException::withMessages([
                'department_ids' => 'تعذر التحقق من الإدارات المحددة.',
            ]);
        }

        if ($departments->contains(fn ($department) => !$department->isDepartment())) {
            throw ValidationException::withMessages([
                'department_ids' => 'يمكن ربط المساهم بإدارات فقط.',
            ]);
        }

        $companyIds = $departments->pluck('parent_id')->filter()->unique()->values();

        if ($companyIds->count() > 1) {
            throw ValidationException::withMessages([
                'department_ids' => 'يجب أن تكون كل الإدارات المختارة تابعة لنفس الشركة.',
            ]);
        }

        if ($companyId && $companyIds->first() !== $companyId) {
            throw ValidationException::withMessages([
                'department_ids' => 'الإدارات المختارة لا تنتمي إلى الشركة المحددة.',
            ]);
        }

        return $departmentIds;
    }

    private function validateManagedCompanies(array $companyIds): array
    {
        $companyIds = collect($companyIds)
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        if (empty($companyIds)) {
            return [];
        }

        $companies = Category::whereIn('id', $companyIds)->get();

        if ($companies->count() !== count($companyIds)) {
            throw ValidationException::withMessages([
                'managed_company_ids' => 'تعذر التحقق من الشركات المحددة للإدارة.',
            ]);
        }

        if ($companies->contains(fn (Category $company) => !$company->isCompany())) {
            throw ValidationException::withMessages([
                'managed_company_ids' => 'يمكن اختيار الشركات الرئيسية فقط في حقل مدير شركة.',
            ]);
        }

        return $companyIds;
    }

    private function syncContributorUser(Contributor $contributor, array $validated, string $loginEmail, ?string $plainPassword): User
    {
        $user = $contributor->user ?: new User();

        $user->name = $validated['name'];
        $user->email = $loginEmail;
        $user->phone = $validated['phone_num'] ?? null;
        $user->id_number = $validated['id_number'];

        if ($plainPassword) {
            $user->password = Hash::make($plainPassword);
        }

        $user->save();

        return $user;
    }

    private function syncLinkedUserDepartments(Contributor $contributor, array $departmentIds): void
    {
        $contributor->loadMissing('user');

        if ($contributor->user) {
            $contributor->user->categories()->sync($departmentIds);
        }
    }
}
