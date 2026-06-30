<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ToyyibpayController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\DonationAllocationController;
use App\Http\Controllers\DonationAllocationCategoryController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ParticipantController;
use App\Models\User;
use App\Models\Event;
use App\Models\Donation;
use App\Models\Participant;
use Barryvdh\DomPDF\Facade\Pdf;

// ========== HOMEPAGE WITH DYNAMIC STATS ==========
Route::get('/', [LandingController::class, 'index'])->name('home');

// ========== PAYMENT ROUTES ==========
Route::get('/checkout', [ToyyibpayController::class, 'showCheckout'])->name('checkout');
Route::post('/pay', [ToyyibpayController::class, 'createBill'])->name('pay.bill');
Route::post('/payment/callback', [ToyyibpayController::class, 'handleCallback'])->name('payment.callback');
Route::get('/payment/callback', [ToyyibpayController::class, 'handleCallback'])->name('payment.return');
Route::view('/payment/status', 'payment_status')->name('payment.status');


// ========== STRIPE PAYMENT ROUTES ==========
Route::prefix('stripe')->name('stripe.')->group(function () {
    Route::post('/create-checkout-session', [App\Http\Controllers\StripePaymentController::class, 'createCheckoutSession'])
        ->name('create-checkout-session');
    Route::get('/success', [App\Http\Controllers\StripePaymentController::class, 'success'])
        ->name('success');
    Route::get('/cancel', [App\Http\Controllers\StripePaymentController::class, 'cancel'])
        ->name('cancel');
    Route::get('/donation-success', [App\Http\Controllers\StripePaymentController::class, 'showSuccess'])
        ->name('donation.success');
    Route::get('/donation-failed', [App\Http\Controllers\StripePaymentController::class, 'showFailed'])
        ->name('donation.failed');
});

// ========== GUEST (NOT LOGGED IN) ROUTES ==========
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
    
    // Registration
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
    
    // Email Verification
    Route::get('/verify-email', [RegisterController::class, 'showVerificationForm'])->name('verification.show');
    Route::post('/verify-code', [RegisterController::class, 'verifyCode'])->name('verification.verify');
    Route::post('/resend-code', [RegisterController::class, 'resendCode'])->name('verification.resend');
    
    // Password Reset
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetCode'])->name('password.email');
    Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset.form');
    Route::post('/reset-password', [ForgotPasswordController::class, 'verifyResetCode'])->name('password.verify.code');
    Route::get('/update-password', [ForgotPasswordController::class, 'showUpdatePasswordForm'])->name('password.update.form');
    Route::post('/update-password', [ForgotPasswordController::class, 'updatePassword'])->name('password.update');
    Route::post('/resend-reset-code', [ForgotPasswordController::class, 'resendResetCode'])->name('password.resend');
});

// ========== TEST ROUTES ==========
Route::get('/test', function () {
    return view('test');
});


// ========== LOGOUT ROUTE ==========
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout')->middleware('auth');

// ========== PROTECTED USER ROUTES ==========
Route::middleware('auth')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [UserController::class, 'showDashboard'])->name('dashboard');


    // ========== USER PROFILE ROUTES ==========
    Route::get('/profile', [UserController::class, 'showProfile'])->name('user.profile.index');
    Route::put('/profile/update', [UserController::class, 'updateProfile'])->name('user.profile.update');
    Route::patch('/profile/remove-picture', [UserController::class, 'removeProfilePicture'])->name('user.profile.remove-picture');
    Route::put('/profile/password', [UserController::class, 'updatePassword'])->name('user.password.update');

    // ========== DONATION ROUTES ==========
    Route::get('/donations', [DonationController::class, 'userDonations'])->name('user.donations');
    Route::get('/donations/data', [DonationController::class, 'getUserDonationsData'])->name('user.donations.data');
    Route::get('/donate', function () {
        return view('user.donation.donate');
    })->name('user.donate');

    // Receipt Routes (Accessible by both user and admin)
    Route::get('/receipt/{donationId}/view', [DonationController::class, 'viewReceipt'])->name('donation.receipt');
    Route::get('/receipt/{donationId}/download', [DonationController::class, 'downloadReceipt'])->name('donation.receipt.download');
    
    // ========== EVENT ROUTES ==========
    // Public event viewing (upcoming events - for volunteering)
    Route::get('/events/upcoming', [EventController::class, 'index'])->name('user.upcomingevents');
    
    // My Events (events created by the user)
    Route::get('/events/my-events', [EventController::class, 'myEvents'])->name('user.myEvents');
    Route::get('/events/my-events/data', [EventController::class, 'getMyEventsData'])->name('user.myEvents.data');
    
    // Create Event
    Route::get('/events/create', [EventController::class, 'create'])->name('user.events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    
    // Event CRUD - IMPORTANT: Place show route BEFORE edit to avoid conflicts
    Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');
    Route::get('/events/{id}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{id}', [EventController::class, 'update'])->name('events.update');
    Route::get('/events/public/{id}', [EventController::class, 'publicShow'])->name('events.public.show');
    
    // AJAX routes for conflict checking
    Route::post('/events/get-booked-sessions-for-range', [EventController::class, 'getBookedSessionsForRange'])->name('events.getBookedSessionsForRange');
    
    // ========== DOCUMENT DOWNLOAD ROUTE ==========
    // Secure document download (only for event owners)
    Route::get('/document/download/{eventId}', [DocumentController::class, 'downloadDocument'])
        ->name('document.download');

    // ========== PARTICIPANT ROUTES ==========
    Route::get('/my-registrations/data', [ParticipantController::class, 'getMyRegistrationsData'])->name('participant.my-registrations.data');
    Route::post('/participant/register/{eventId}', [ParticipantController::class, 'register'])->name('participant.register');
    Route::post('/participant/cancel/{eventId}', [ParticipantController::class, 'cancel'])->name('participant.cancel');
    Route::delete('/participant/cancel-own/{eventId}', [ParticipantController::class, 'cancelOwnRegistration'])->name('participant.cancel-own');
    Route::get('/my-registrations', [ParticipantController::class, 'myRegistrations'])->name('participant.my-registrations');
    Route::get('/participant/status/{eventId}', [ParticipantController::class, 'checkStatus'])->name('participant.status');
    Route::get('/participant/event/{eventId}', [ParticipantController::class, 'getEventParticipants'])->name('participant.event-participants');
    Route::get('/participant/check-conflict/{eventId}', [ParticipantController::class, 'checkTimeConflict'])->name('participant.check-conflict');
});

// ========== PROTECTED ADMIN ROUTES ==========
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [UserController::class, 'showAdminDashboard'])->name('dashboard');
    Route::get('/dashboard/events-data', [UserController::class, 'getDashboardEventsData'])->name('dashboard.events.data');
    
    // Profile Management
    Route::get('/profile', function () {
        return view('admin.profile.index');
    })->name('profile.index');
    Route::patch('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::patch('/profile/remove-picture', [UserController::class, 'removeProfilePicture'])->name('profile.remove-picture');
    Route::put('/password/update', [UserController::class, 'updatePassword'])->name('password.update');
    Route::delete('/profile/delete', [UserController::class, 'destroy'])->name('profile.destroy');
    
    // User Management (regular users)
    Route::get('/listusers', [UserController::class, 'listUsersAdmin'])->name('users.index');
    Route::get('/user/profile/{id}', [UserController::class, 'userDetailsAdmin'])->name('users.profile');
    Route::get('/user/profile/update/{id}', [UserController::class, 'adminUpdateUserPage'])->name('users.update');
    Route::patch('/user/profile/update/{id}', [UserController::class, 'adminUpdateDetailsUser'])->name('updateDetails.User');
    Route::patch('/user/removePicture/{id}', [UserController::class, 'adminRemoveUserProfilePicture'])->name('removePicture.user');
    Route::put('/user/password/{id}', [UserController::class, 'adminUpdateUserPassword'])->name('updatePassword.user');
    Route::delete('/user/delete/{id}', [UserController::class, 'adminDestroyUser'])->name('destroy.user');
    Route::patch('/admin/users/{id}/toggle-status', [UserController::class, 'toggleUserStatus'])->name('admin.users.toggle-status');
    // User Report
    Route::get('/users/report', [UserController::class, 'userReport'])->name('users.report');
    
    // Admin Management
    Route::get('/listadministrators', [UserController::class, 'listAdminsAdmin'])->name('admins.index');
    Route::post('/createAdmin', [UserController::class, 'storeAdmin'])->name('storeAdmin');
    Route::patch('/admins/{id}/toggle-status', [UserController::class, 'toggleAdminStatus'])->name('admins.toggle-status');
    // Admin Report
    Route::get('/admins/report', [UserController::class, 'adminReport'])->name('admins.report');
    
    // ============================================
    // EVENT MANAGEMENT (Admin views)
    // ============================================
    
    // Pending Events
    Route::get('/pendingevent', [EventController::class, 'getPendingEventsView'])->name('pendingevent');
    Route::get('/events/pending/data', [EventController::class, 'getPendingEvents'])->name('events.pending.data');
    
    Route::get('/events/create', [EventController::class, 'adminCreate'])->name('events.create');
    Route::post('/events', [EventController::class, 'adminStore'])->name('events.store');


    // Upcoming Events
    Route::get('/upcomingevent', [EventController::class, 'getUpcomingEvents'])->name('upcomingevent');
    Route::get('/events/upcoming/data', [EventController::class, 'getUpcomingEvents'])->name('events.upcoming.data');
    
    // Past Events
    Route::get('/pastevent', [EventController::class, 'getPastEventsView'])->name('pastevent');
    Route::get('/events/past/data', [EventController::class, 'getPastEvents'])->name('events.past.data');
    
    Route::get('/events/{id}/edit-status', [EventController::class, 'adminEditStatus'])->name('events.edit-status');
    Route::put('/events/{id}/update-status', [EventController::class, 'adminUpdateStatus'])->name('events.update-status');


    // Event Report
    Route::get('/events/report', [EventController::class, 'generateEventReport'])->name('events.report');

    // Event Details & Actions

    // Admin - Get event participants (AJAX)
    Route::get('/events/{id}', [EventController::class, 'adminShow'])->name('events.show');
    Route::get('/events/{id}/edit', [EventController::class, 'adminEdit'])->name('events.edit');
    Route::put('/events/{id}', [EventController::class, 'adminUpdate'])->name('events.update');
    Route::post('/events/{id}/approve', [EventController::class, 'adminApprove'])->name('events.approve');
    Route::post('/events/{id}/reject', [EventController::class, 'adminReject'])->name('events.reject');
    Route::post('/events/{id}/request-update', [EventController::class, 'adminRequestUpdate'])->name('events.request-update');
    Route::post('/events/{id}/publish', [EventController::class, 'adminPublish'])->name('events.publish');
    Route::post('/events/{id}/unpublish', [EventController::class, 'adminUnpublish'])->name('events.unpublish');
    

    // ========== PARTICIPANT MANAGEMENT ROUTES =========
    Route::get('/events/{id}/participants', [ParticipantController::class, 'getEventParticipants'])->name('events.participants');
    Route::post('/events/{eventId}/cancel-participant', [ParticipantController::class, 'adminCancelParticipant'])->name('events.cancel-participant');
    
    // ========== DONATION MANAGEMENT ROUTES ==========

    // ========== CATEGORY ROUTES (MUST BE BEFORE /donations/{id}) ==========
    Route::get('/donations/categories', [DonationAllocationCategoryController::class, 'index'])->name('donations.categories.index');
    Route::get('/donations/categories/active', [DonationAllocationCategoryController::class, 'getActiveCategories'])->name('donations.categories.active');
    Route::post('/donations/categories', [DonationAllocationCategoryController::class, 'store'])->name('donations.categories.store');
    Route::get('/donations/categories/{id}', [DonationAllocationCategoryController::class, 'show'])->name('donations.categories.show');
    Route::put('/donations/categories/{id}', [DonationAllocationCategoryController::class, 'update'])->name('donations.categories.update');
    Route::delete('/donations/categories/{id}', [DonationAllocationCategoryController::class, 'destroy'])->name('donations.categories.destroy');
    Route::patch('/donations/categories/{id}/toggle-active', [DonationAllocationCategoryController::class, 'toggleActive'])->name('donations.categories.toggle-active');
    Route::post('/donations/categories/bulk-destroy', [DonationAllocationCategoryController::class, 'bulkDestroy'])->name('donations.categories.bulk-destroy');

    // ========== ALLOCATION ROUTES ==========
    Route::get('/donations/allocations', [DonationAllocationController::class, 'index'])->name('donations.allocations');
    Route::get('/donations/allocations/data', [DonationAllocationController::class, 'getAllocationsData'])->name('donations.allocations.data');
    Route::post('/donations/allocations', [DonationAllocationController::class, 'store'])->name('donations.allocations.store');
    Route::put('/donations/allocations/{id}', [DonationAllocationController::class, 'update'])->name('donations.allocations.update');
    Route::delete('/donations/allocations/{id}', [DonationAllocationController::class, 'destroy'])->name('donations.allocations.destroy');
    Route::post('/donations/allocations/recalculate', [DonationAllocationController::class, 'recalculate'])->name('donations.allocations.recalculate');
    Route::get('/donations/allocations/summary/{month}', [DonationAllocationController::class, 'getSummary'])->name('donations.allocations.summary');
    Route::post('/donations/allocations/new-category', [DonationAllocationController::class, 'storeWithNewCategory'])->name('donations.allocations.new-category');

    // Main Donation routes (PUT THIS LAST - catches /donations/{id})
    Route::get('/donations', [DonationController::class, 'index'])->name('donations');
    Route::get('/donations/report', [DonationController::class, 'generateReport'])->name('donations.report');
    Route::get('/donations/export', [DonationController::class, 'exportDonations'])->name('donations.export');
    Route::get('/donations/data', [DonationController::class, 'getDonationsData'])->name('donations.data');
    Route::post('/donations', [DonationController::class, 'store'])->name('storeDonation');
    Route::get('/donations/{id}', [DonationController::class, 'show'])->name('viewDonation');

    
});