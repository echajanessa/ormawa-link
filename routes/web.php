<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\layouts\WithoutMenu;
use App\Http\Controllers\layouts\WithoutNavbar;
use App\Http\Controllers\layouts\Fluid;
use App\Http\Controllers\layouts\Container;
use App\Http\Controllers\layouts\Blank;
use App\Http\Controllers\pages\AccountSettingsAccount;
use App\Http\Controllers\pages\AccountSettingsNotifications;
use App\Http\Controllers\pages\AccountSettingsConnections;
use App\Http\Controllers\cards\CardBasic;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentApprovalController;
use App\Http\Controllers\DocumentHistoryController;
use App\Http\Controllers\DocumentLpjController;
use App\Http\Controllers\DocumentProposalController;
use App\Http\Controllers\DocumentSubmissionController;
use App\Http\Controllers\DocumentTrackingController;
use App\Http\Controllers\RegulationManagementController;
use App\Http\Controllers\user_interface\Accordion;
use App\Http\Controllers\user_interface\Alerts;
use App\Http\Controllers\user_interface\Badges;
use App\Http\Controllers\user_interface\Buttons;
use App\Http\Controllers\user_interface\Carousel;
use App\Http\Controllers\user_interface\Collapse;
use App\Http\Controllers\user_interface\Dropdowns;
use App\Http\Controllers\user_interface\Footer;
use App\Http\Controllers\user_interface\ListGroups;
use App\Http\Controllers\user_interface\Modals;
use App\Http\Controllers\user_interface\Navbar;
use App\Http\Controllers\user_interface\Offcanvas;
use App\Http\Controllers\user_interface\PaginationBreadcrumbs;
use App\Http\Controllers\user_interface\Progress;
use App\Http\Controllers\user_interface\Spinners;
use App\Http\Controllers\user_interface\TabsPills;
use App\Http\Controllers\user_interface\Toasts;
use App\Http\Controllers\user_interface\TooltipsPopovers;
use App\Http\Controllers\user_interface\Typography;
use App\Http\Controllers\extended_ui\PerfectScrollbar;
use App\Http\Controllers\extended_ui\TextDivider;
use App\Http\Controllers\icons\Boxicons;
use App\Http\Controllers\form_elements\BasicInput;
use App\Http\Controllers\form_elements\InputGroups;
use App\Http\Controllers\form_layouts\VerticalForm;
use App\Http\Controllers\form_layouts\HorizontalForm;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\tables\Basic as TablesBasic;
use App\Http\Controllers\TrackingDetailController;
use App\Http\Controllers\UserManagementController;
use App\Models\User;

Route::get('/', function () {
	return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'showLpjReminder'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/search', [SearchController::class, 'search'])->name('search');

    Route::get('/pengajuan-proposal/create', [DocumentSubmissionController::class, 'create'])->name('submission-proposal.create');
    Route::post('/pengajuan-proposal', [DocumentSubmissionController::class, 'store'])->name('submission-proposal.store');
    Route::get('/pengajuan-proposal/revisi/edit/{submissionId}', [DocumentSubmissionController::class, 'editProposal'])->name('submission-proposal.edit');
    Route::post('/pengajuan-proposal/revisi/{submissionId}', [DocumentSubmissionController::class, 'reUploadProposal'])->name('submission-proposal.revise');

    Route::get('/pengajuan-lpj', [DocumentLpjController::class, 'index'])->name('submission-lpj');
    Route::get('/pengajuan-lpj/revisi/edit/{submissionId}', [DocumentSubmissionController::class, 'editLpj'])->name('submission-lpj.edit');
    Route::post('/pengajuan-lpj/revisi/{submissionId}', [DocumentSubmissionController::class, 'reUploadLpj'])->name('submission-lpj.revise');

    Route::get('/persetujuan-dokumen', [DocumentApprovalController::class, 'index'])->name('document-approval.index');
    Route::get('/persetujuan-dokumen/detail/{id}', [DocumentApprovalController::class, 'show'])->name('document-approval.show');
    Route::post('/persetujuan-dokumen/{submissionId}/process', [DocumentApprovalController::class, 'process'])->name('document-approval.process');

    Route::get('/pemantauan-proses', [DocumentTrackingController::class, 'index'])->name('document-tracking.index');
    Route::get('/pemantauan-proses/detail/{id}', [DocumentTrackingController::class, 'show'])->name('tracking-detail');

    Route::get('/riwayat-dokumen', [DocumentHistoryController::class, 'index'])->name('document-history');
    Route::get('/riwayat-pengajuan/detail/{id}', [DocumentHistoryController::class, 'show'])->name('history-detail');

    Route::get('/manajemen-regulasi', [RegulationManagementController::class, 'index'])->name('regulation-management.index');
    Route::post('/manajemen-regulasi', [RegulationManagementController::class, 'store'])->name('regulation-management.store');
    Route::get('/manajemen-regulasi/{id}/edit', [RegulationManagementController::class, 'edit'])->name('regulation-management.edit');
    Route::put('/manajemen-regulasi/{id}', [RegulationManagementController::class, 'update'])->name('regulation-management.update');
    Route::delete('/manajemen-regulasi/{id}', [RegulationManagementController::class, 'destroy'])->name('regulation-management.destroy');

    Route::get('/manajemen-akun', [UserManagementController::class, 'index'])->name('user-management.index');
    Route::post('/manajemen-akun', [UserManagementController::class, 'store'])->name('user-management.store');
    Route::get('/manajemen-akun/{id}/edit', [UserManagementController::class, 'edit'])->name('user-management.edit');
    Route::put('/manajemen-akun/{id}', [UserManagementController::class, 'update'])->name('user-management.update');
    Route::delete('/manajemen-akun/{id}', [UserManagementController::class, 'destroy'])->name('user-management.destroy');

    // layout
    Route::get('/layouts/without-menu', [WithoutMenu::class, 'index'])->name('layouts-without-menu');
    Route::get('/layouts/without-navbar', [WithoutNavbar::class, 'index'])->name('layouts-without-navbar');
    Route::get('/layouts/fluid', [Fluid::class, 'index'])->name('layouts-fluid');
    Route::get('/layouts/container', [Container::class, 'index'])->name('layouts-container');
    Route::get('/layouts/blank', [Blank::class, 'index'])->name('layouts-blank');

    // pages
    Route::get('/pages/account-settings-account', [AccountSettingsAccount::class, 'index'])->name('pages-account-settings-account');
    Route::get('/pages/account-settings-notifications', [AccountSettingsNotifications::class, 'index'])->name('pages-account-settings-notifications');
    Route::get('/pages/account-settings-connections', [AccountSettingsConnections::class, 'index'])->name('pages-account-settings-connections');

    // cards
    Route::get('/cards/basic', [CardBasic::class, 'index'])->name('cards-basic');

    // User Interface
    Route::get('/ui/accordion', [Accordion::class, 'index'])->name('ui-accordion');
    Route::get('/ui/alerts', [Alerts::class, 'index'])->name('ui-alerts');
    Route::get('/ui/badges', [Badges::class, 'index'])->name('ui-badges');
    Route::get('/ui/buttons', [Buttons::class, 'index'])->name('ui-buttons');
    Route::get('/ui/carousel', [Carousel::class, 'index'])->name('ui-carousel');
    Route::get('/ui/collapse', [Collapse::class, 'index'])->name('ui-collapse');
    Route::get('/ui/dropdowns', [Dropdowns::class, 'index'])->name('ui-dropdowns');
    Route::get('/ui/footer', [Footer::class, 'index'])->name('ui-footer');
    Route::get('/ui/list-groups', [ListGroups::class, 'index'])->name('ui-list-groups');
    Route::get('/ui/modals', [Modals::class, 'index'])->name('ui-modals');
    Route::get('/ui/navbar', [Navbar::class, 'index'])->name('ui-navbar');
    Route::get('/ui/offcanvas', [Offcanvas::class, 'index'])->name('ui-offcanvas');
    Route::get('/ui/pagination-breadcrumbs', [PaginationBreadcrumbs::class, 'index'])->name('ui-pagination-breadcrumbs');
    Route::get('/ui/progress', [Progress::class, 'index'])->name('ui-progress');
    Route::get('/ui/spinners', [Spinners::class, 'index'])->name('ui-spinners');
    Route::get('/ui/tabs-pills', [TabsPills::class, 'index'])->name('ui-tabs-pills');
    Route::get('/ui/toasts', [Toasts::class, 'index'])->name('ui-toasts');
    Route::get('/ui/tooltips-popovers', [TooltipsPopovers::class, 'index'])->name('ui-tooltips-popovers');
    Route::get('/ui/typography', [Typography::class, 'index'])->name('ui-typography');

    // extended ui
    Route::get('/ui/scrollbar', [PerfectScrollbar::class, 'index'])->name('extended-ui-perfect-scrollbar');
    Route::get('/ui/text-divider', [TextDivider::class, 'index'])->name('extended-ui-text-divider');

    // icons
    Route::get('/icons/boxicons', [Boxicons::class, 'index'])->name('icons-boxicons');

    // form elements
    Route::get('/forms/basic-inputs', [BasicInput::class, 'index'])->name('forms-basic-inputs');
    Route::get('/forms/input-groups', [InputGroups::class, 'index'])->name('forms-input-groups');

    // form layouts
    Route::get('/form/layouts-vertical', [VerticalForm::class, 'index'])->name('form-layouts-vertical');
    Route::get('/form/layouts-horizontal', [HorizontalForm::class, 'index'])->name('form-layouts-horizontal');

    // tables
    Route::get('/tables/basic', [TablesBasic::class, 'index'])->name('tables-basic');
});

require __DIR__ . '/auth.php';
