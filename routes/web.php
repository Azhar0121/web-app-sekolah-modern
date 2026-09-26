<?php

use App\Http\Controllers\Admin\AcademicYearController;
use App\Http\Controllers\Admin\AnnouncementController as AdminAnnouncementController;
use App\Http\Controllers\Admin\BillingController as AdminBillingController;
use App\Http\Controllers\Admin\ClassroomController;
use App\Http\Controllers\Admin\CorrespondenceController;
use App\Http\Controllers\Admin\LeaveRequestController as AdminLeaveRequestController;
use App\Http\Controllers\Admin\PpdbController as AdminPpdbController;
use App\Http\Controllers\Admin\ReportCardController as AdminReportCardController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ScheduleController as AdminScheduleController;
use App\Http\Controllers\Admin\SemesterController;
use App\Http\Controllers\Admin\StudentPlacementController;
use App\Http\Controllers\Admin\StudentProfileController as AdminStudentProfileController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TeachingAssignmentController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Kepsek\DashboardController as KepsekDashboardController;
use App\Http\Controllers\Guru\AttendanceController as GuruAttendanceController;
use App\Http\Controllers\Guru\CommunicationController as GuruCommunicationController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Guru\GradeController as GuruGradeController;
use App\Http\Controllers\Guru\MaterialController as GuruMaterialController;
use App\Http\Controllers\Guru\ScheduleController as GuruScheduleController;
use App\Http\Controllers\Guru\StudentProfileController as GuruStudentProfileController;
use App\Http\Controllers\Guru\TaskController as GuruTaskController;
use App\Http\Controllers\Ortu\AttendanceController as OrtuAttendanceController;
use App\Http\Controllers\Ortu\BillingController as OrtuBillingController;
use App\Http\Controllers\Ortu\CommunicationController as OrtuCommunicationController;
use App\Http\Controllers\Ortu\DashboardController as OrtuDashboardController;
use App\Http\Controllers\Ortu\GradeController as OrtuGradeController;
use App\Http\Controllers\Ortu\LeaveRequestController as OrtuLeaveRequestController;
use App\Http\Controllers\Ortu\ReportCardController as OrtuReportCardController;
use App\Http\Controllers\Ortu\ScheduleController as OrtuScheduleController;
use App\Http\Controllers\PpdbController;
use App\Http\Controllers\Siswa\AttendanceController as SiswaAttendanceController;
use App\Http\Controllers\Siswa\GradeController as SiswaGradeController;
use App\Http\Controllers\Siswa\MaterialController as SiswaMaterialController;
use App\Http\Controllers\Siswa\ProfileController as SiswaProfileController;
use App\Http\Controllers\Siswa\QrCodeController as SiswaQrCodeController;
use App\Http\Controllers\Siswa\ScheduleController as SiswaScheduleController;
use App\Http\Controllers\Siswa\TaskController as SiswaTaskController;
use App\Http\Controllers\Tu\DashboardController as TuDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ================= AUTH =================
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});

// ================= PPDB ONLINE (PUBLIK, TANPA LOGIN) =================
Route::prefix('ppdb')->name('ppdb.')->group(function () {
    Route::get('/', [PpdbController::class, 'index'])->name('index');
    Route::get('/daftar', [PpdbController::class, 'create'])->name('create');
    Route::post('/daftar', [PpdbController::class, 'store'])->name('store');
    Route::get('/sukses/{registrationNumber}', [PpdbController::class, 'success'])->name('sukses');
    Route::get('/cetak/{registrationNumber}', [PpdbController::class, 'print'])->name('cetak');
    Route::get('/cek-status', [PpdbController::class, 'checkStatusForm'])->name('cek-status.form');
    Route::post('/cek-status', [PpdbController::class, 'checkStatus'])->name('cek-status');
    Route::get('/lupa-nomor', [PpdbController::class, 'forgotNumberForm'])->name('lupa-nomor.form');
    Route::post('/lupa-nomor', [PpdbController::class, 'forgotNumber'])->name('lupa-nomor');
});

// ================= PORTAL SUPER ADMIN =================
Route::middleware(['auth', 'role:super-admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::view('/dashboard', 'admin.dashboard')->name('dashboard');

    // User Management
    Route::resource('users', UserController::class)->except(['show']);

    // Role & Permission Management
    Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update');

    // Master Data Akademik: Tahun Ajaran & Semester
    Route::resource('academic-years', AcademicYearController::class)->except(['show']);
    Route::post('academic-years/{academicYear}/semesters', [SemesterController::class, 'store'])
        ->name('academic-years.semesters.store');
    Route::post('academic-years/{academicYear}/semesters/{semester}/activate', [SemesterController::class, 'activate'])
        ->name('academic-years.semesters.activate');
    Route::delete('academic-years/{academicYear}/semesters/{semester}', [SemesterController::class, 'destroy'])
        ->name('academic-years.semesters.destroy');

    // Master Data Akademik: Mata Pelajaran
    Route::resource('subjects', SubjectController::class)->except(['show']);

    // Master Data Akademik: Kelas
    Route::resource('classrooms', ClassroomController::class)->except(['show']);

    // Penugasan Mengajar (guru-kelas-mapel per tahun ajaran)
    Route::resource('teaching-assignments', TeachingAssignmentController::class)->except(['show']);

    // Penempatan Siswa ke Kelas per tahun ajaran
    Route::get('student-placements', [StudentPlacementController::class, 'index'])->name('student-placements.index');
    Route::get('student-placements/{classroom}', [StudentPlacementController::class, 'manage'])->name('student-placements.manage');
    Route::post('student-placements/{classroom}', [StudentPlacementController::class, 'store'])->name('student-placements.store');
    Route::delete('student-placements/entry/{classroomStudent}', [StudentPlacementController::class, 'destroy'])->name('student-placements.destroy');

    // Jadwal Pelajaran
    Route::resource('schedules', AdminScheduleController::class)->except(['show']);
});

// ================= KELOLA PPDB (SUPER ADMIN & TU, by permission) =================
Route::middleware(['auth', 'permission:ppdb.manage'])->prefix('admin/ppdb')->name('admin.ppdb.')->group(function () {
    Route::get('/', [AdminPpdbController::class, 'index'])->name('index');
    Route::get('/{ppdbRegistration}', [AdminPpdbController::class, 'show'])->name('show');
    Route::put('/{ppdbRegistration}/status', [AdminPpdbController::class, 'updateStatus'])->name('update-status');
    Route::put('/{ppdbRegistration}/re-registration', [AdminPpdbController::class, 'confirmReRegistration'])->name('confirm-re-registration');
});

// ================= PERSURATAN DIGITAL & KEARSIPAN (SUPER ADMIN & TU, by permission) =================
Route::middleware(['auth', 'permission:persuratan.manage'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('correspondences', CorrespondenceController::class);
});

// ================= BIODATA SISWA (TU & SUPER ADMIN, by permission) =================
Route::middleware(['auth', 'permission:siswa.manage'])->prefix('admin/siswa-profiles')->name('admin.student-profiles.')->group(function () {
    Route::get('/', [AdminStudentProfileController::class, 'index'])->name('index');
    Route::get('/{student}/edit', [AdminStudentProfileController::class, 'edit'])->name('edit');
    Route::put('/{student}', [AdminStudentProfileController::class, 'update'])->name('update');
    Route::post('/{student}/sync-ppdb', [AdminStudentProfileController::class, 'syncFromPpdb'])->name('sync-ppdb');
});

// ================= PORTAL GURU / WALI KELAS =================
Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('dashboard');

    // Materi Pembelajaran (nested di bawah kelas+mapel yang diampu)
    Route::resource('teaching-assignments.materials', GuruMaterialController::class)
        ->parameters(['materials' => 'material'])
        ->except(['show']);

    // Tugas & Koreksi Pengumpulan
    Route::resource('teaching-assignments.tasks', GuruTaskController::class)
        ->parameters(['tasks' => 'task'])
        ->except(['show']);
    Route::get('teaching-assignments/{teachingAssignment}/tasks/{task}/submissions', [GuruTaskController::class, 'submissions'])
        ->name('teaching-assignments.tasks.submissions');
    Route::put('teaching-assignments/{teachingAssignment}/tasks/{task}/submissions/{submission}/grade', [GuruTaskController::class, 'grade'])
        ->name('teaching-assignments.tasks.submissions.grade');

    // Nilai (input & bobot penilaian)
    Route::get('teaching-assignments/{teachingAssignment}/nilai', [GuruGradeController::class, 'index'])
        ->name('teaching-assignments.grades.index');
    Route::put('teaching-assignments/{teachingAssignment}/nilai/bobot', [GuruGradeController::class, 'updateWeight'])
        ->name('teaching-assignments.grades.update-weight');
    Route::post('teaching-assignments/{teachingAssignment}/nilai', [GuruGradeController::class, 'storeBatch'])
        ->name('teaching-assignments.grades.store-batch');
    Route::delete('teaching-assignments/{teachingAssignment}/nilai/{grade}', [GuruGradeController::class, 'destroy'])
        ->name('teaching-assignments.grades.destroy');

    // Jadwal Mengajar Pribadi
    Route::get('/jadwal', [GuruScheduleController::class, 'index'])->name('schedule.index');

    // Biodata Siswa (lihat saja, dibatasi ke siswa di kelas yang diampu)
    Route::get('/siswa', [GuruStudentProfileController::class, 'index'])->name('student-profile.index');
    Route::get('/siswa/{student}/biodata', [GuruStudentProfileController::class, 'show'])->name('student-profile.show');

    // Presensi/Absensi QR Code
    Route::get('/presensi', [GuruAttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/presensi/{schedule}/kelola', [GuruAttendanceController::class, 'session'])->name('attendance.session');
    Route::post('/presensi/sesi/{attendanceSession}/scan', [GuruAttendanceController::class, 'scan'])->name('attendance.scan');
    Route::put('/presensi/sesi/{attendanceSession}/siswa/{student}/status', [GuruAttendanceController::class, 'updateStatus'])->name('attendance.update-status');
    Route::post('/presensi/sesi/{attendanceSession}/tutup', [GuruAttendanceController::class, 'close'])->name('attendance.close');
    Route::post('/presensi/sesi/{attendanceSession}/buka-kembali', [GuruAttendanceController::class, 'reopen'])->name('attendance.reopen');

    // Ruang Komunikasi Terarah (Orang Tua)
    Route::get('/komunikasi', [GuruCommunicationController::class, 'index'])->name('communication.index');
    Route::post('/komunikasi', [GuruCommunicationController::class, 'store'])->name('communication.store');
    Route::post('/komunikasi/{thread}/balas', [GuruCommunicationController::class, 'reply'])->name('communication.reply');
    Route::post('/komunikasi/{thread}/toggle-status', [GuruCommunicationController::class, 'toggleStatus'])->name('communication.toggle-status');
});

// ================= PORTAL SISWA =================
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::view('/dashboard', 'siswa.dashboard')->name('dashboard');

    // Jadwal Pelajaran
    Route::get('/jadwal', [SiswaScheduleController::class, 'index'])->name('schedule.index');

    // Nilai Rapor & Grafik
    Route::get('/nilai', [SiswaGradeController::class, 'index'])->name('grades.index');

    // Biodata Diri
    Route::get('/biodata', [SiswaProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/biodata', [SiswaProfileController::class, 'update'])->name('profile.update');
    Route::post('/biodata/ganti-password', [SiswaProfileController::class, 'updatePassword'])->name('profile.update-password');

    // Presensi/Absensi QR Code
    Route::get('/kartu-pelajar', [SiswaQrCodeController::class, 'show'])->name('qr-code.show');
    Route::post('/kartu-pelajar/refresh', [SiswaQrCodeController::class, 'refresh'])->name('qr-code.refresh');
    Route::get('/presensi', [SiswaAttendanceController::class, 'index'])->name('attendance.index');

    // Materi Pembelajaran
    Route::get('/materi', [SiswaMaterialController::class, 'index'])->name('materials.index');
    Route::get('/materi/{material}/unduh', [SiswaMaterialController::class, 'download'])->name('materials.download');

    // Tugas & Pengumpulan
    Route::get('/tugas', [SiswaTaskController::class, 'index'])->name('tasks.index');
    Route::get('/tugas/{task}', [SiswaTaskController::class, 'show'])->name('tasks.show');
    Route::post('/tugas/{task}', [SiswaTaskController::class, 'store'])->name('tasks.store');
    Route::get('/tugas/{task}/lampiran', [SiswaTaskController::class, 'downloadAttachment'])->name('tasks.download-attachment');
    Route::get('/pengumpulan/{submission}/unduh', [SiswaTaskController::class, 'downloadSubmission'])->name('tasks.download-submission');
});

// ================= PORTAL ORANG TUA / WALI =================
Route::middleware(['auth', 'role:ortu'])->prefix('ortu')->name('ortu.')->group(function () {
    Route::get('/dashboard', [OrtuDashboardController::class, 'index'])->name('dashboard');
    Route::get('/anak/{student}/presensi', [OrtuAttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/anak/{student}/nilai', [OrtuGradeController::class, 'index'])->name('grades.index');
    Route::get('/anak/{student}/jadwal', [OrtuScheduleController::class, 'index'])->name('schedule.index');

    // Pengajuan Izin
    Route::get('/izin', [OrtuLeaveRequestController::class, 'index'])->name('leave-requests.index');
    Route::get('/izin/ajukan', [OrtuLeaveRequestController::class, 'create'])->name('leave-requests.create');
    Route::post('/izin', [OrtuLeaveRequestController::class, 'store'])->name('leave-requests.store');

    // Tagihan
    Route::get('/anak/{student}/tagihan', [OrtuBillingController::class, 'index'])->name('billing.index');

    // Rapor Digital
    Route::get('/anak/{student}/rapor', [OrtuReportCardController::class, 'index'])->name('report-cards.index');
    Route::get('/rapor/{reportCard}/unduh', [OrtuReportCardController::class, 'download'])->name('report-cards.download');

    // Ruang Komunikasi Terarah (Guru)
    Route::get('/komunikasi', [OrtuCommunicationController::class, 'index'])->name('communication.index');
    Route::post('/komunikasi', [OrtuCommunicationController::class, 'store'])->name('communication.store');
    Route::post('/komunikasi/{thread}/balas', [OrtuCommunicationController::class, 'reply'])->name('communication.reply');
});

// ================= PORTAL TATA USAHA =================
Route::middleware(['auth', 'role:tu'])->prefix('tu')->group(function () {
    Route::get('/dashboard', [TuDashboardController::class, 'index'])->name('tu.dashboard');
});

// ================= PENGUMUMAN INTERNAL (Super Admin, TU, Kepsek) =================
Route::middleware(['auth', 'permission:pengumuman.manage'])
    ->prefix('admin/pengumuman')->name('admin.announcements.')
    ->group(function () {
        Route::get('/', [AdminAnnouncementController::class, 'index'])->name('index');
        Route::get('/buat', [AdminAnnouncementController::class, 'create'])->name('create');
        Route::post('/', [AdminAnnouncementController::class, 'store'])->name('store');
        Route::get('/{announcement}/edit', [AdminAnnouncementController::class, 'edit'])->name('edit');
        Route::put('/{announcement}', [AdminAnnouncementController::class, 'update'])->name('update');
        Route::delete('/{announcement}', [AdminAnnouncementController::class, 'destroy'])->name('destroy');
        Route::post('/{announcement}/toggle', [AdminAnnouncementController::class, 'toggle'])->name('toggle');
    });

// ================= PENGAJUAN IZIN (Guru/Admin memproses) =================
Route::middleware(['auth', 'permission:izin.manage'])
    ->prefix('guru/izin-siswa')->name('guru.leave-requests.')
    ->group(function () {
        Route::get('/', [AdminLeaveRequestController::class, 'index'])->name('index');
        Route::post('/{leaveRequest}/proses', [AdminLeaveRequestController::class, 'process'])->name('process');
    });

Route::middleware(['auth', 'permission:izin.manage'])
    ->prefix('admin/izin-siswa')->name('admin.leave-requests.')
    ->group(function () {
        Route::get('/', [AdminLeaveRequestController::class, 'index'])->name('index');
        Route::post('/{leaveRequest}/proses', [AdminLeaveRequestController::class, 'process'])->name('process');
    });

// ================= TAGIHAN SISWA (TU) =================
Route::middleware(['auth', 'permission:billing.manage'])
    ->prefix('admin/tagihan')->name('admin.billing.')
    ->group(function () {
        Route::get('/', [AdminBillingController::class, 'index'])->name('index');
        Route::get('/{student}', [AdminBillingController::class, 'show'])->name('show');
        Route::post('/{student}', [AdminBillingController::class, 'store'])->name('store');
        Route::post('/record/{billing}/konfirmasi', [AdminBillingController::class, 'confirm'])->name('confirm');
        Route::delete('/record/{billing}', [AdminBillingController::class, 'destroy'])->name('destroy');
    });

// ================= RAPOR DIGITAL (TU) =================
Route::middleware(['auth', 'permission:rapor.manage'])
    ->prefix('admin/rapor')->name('admin.report-cards.')
    ->group(function () {
        Route::get('/{student}', [AdminReportCardController::class, 'index'])->name('index');
        Route::post('/{student}', [AdminReportCardController::class, 'store'])->name('store');
        Route::delete('/{reportCard}', [AdminReportCardController::class, 'destroy'])->name('destroy');
    });

// ================= PORTAL KEPALA SEKOLAH =================
Route::middleware(['auth', 'role:kepsek'])->prefix('kepsek')->group(function () {
    Route::get('/dashboard', [KepsekDashboardController::class, 'index'])->name('kepsek.dashboard');
});