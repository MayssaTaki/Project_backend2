@extends('adminlte::page')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-lg-4 col-md-6 col-12 mb-3">
            <div class="info-box bg-gradient-info animate__animated animate__fadeInLeft">
                <span class="info-box-icon"><i class="fas fa-tags"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">All Categories</span>
                    <span class="info-box-number">{{ \App\Models\Category::count() }}</span>
                    <div class="progress"><div class="progress-bar" style="width: 70%"></div></div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-12 mb-3">
            <div class="info-box bg-gradient-info animate__animated animate__fadeInUp">
                <span class="info-box-icon"><i class="fas fa-chalkboard-teacher"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">All Teachers</span>
                    <span class="info-box-number">{{ \App\Models\Teacher::count() }}</span>
                    <div class="progress"><div class="progress-bar" style="width: 50%"></div></div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-12 mb-3">
            <div class="info-box bg-gradient-info animate__animated animate__fadeInRight">
                <span class="info-box-icon"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">All Students</span>
                    <span class="info-box-number">{{ \App\Models\Student::count() }}</span>
                    <div class="progress"><div class="progress-bar" style="width: 30%"></div></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="row">
        {{-- Teacher Status Chart --}}
        <div class="col-md-12 col-lg-6">
            <div class="card shadow-sm border-0 animate__animated animate__fadeInLeft">
                <div class="card-header bg-gradient-info text-white text-center">
                    <h5 class="card-title mb-0">Teacher Status Distribution</h5>
                </div>
                <div class="card-body">
                    <canvas id="teacherStatusChart" width="200" height="250"></canvas>
                </div>
            </div>
        </div>

        {{-- Student Status Chart --}}
        <div class="col-md-12 col-lg-6">
            <div class="card shadow-sm border-0 animate__animated animate__fadeInRight">
                <div class="card-header bg-gradient-info text-white text-center">
                    <h5 class="card-title mb-0">Student Status Distribution</h5>
                </div>
                <div class="card-body">
                    <canvas id="studentStatusChart" width="200" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Include Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

{{-- Chart Script --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Teacher Chart
        const teacherCtx = document.getElementById('teacherStatusChart').getContext('2d');
        new Chart(teacherCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Approved', 'Rejected'],
                datasets: [{
                    data: [
                        {{ $teachers['pending'] }},
                        {{ $teachers['approved'] }},
                        {{ $teachers['rejected'] }}
                    ],
                    backgroundColor: [
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(220, 53, 69, 0.8)'
                    ],
                    borderColor: [
                        'rgba(255, 193, 7, 1)',
                        'rgba(40, 167, 69, 1)',
                        'rgba(220, 53, 69, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Teacher Status Distribution'
                    }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuad',
                    animateRotate: true,
                    animateScale: true
                }
            }
        });

        // Student Chart
        const studentCtx = document.getElementById('studentStatusChart').getContext('2d');
        new Chart(studentCtx, {
            type: 'bar',
            data: {
                labels: ['Active Students', 'Inactive Students'],
                datasets: [{
                    label: 'Number of Students',
                    data: [{{ $students['active'] }}, {{ $students['inactive'] }}],
                    backgroundColor: ['#4CAF50', '#F44336'],
                    borderColor: ['#388E3C', '#D32F2F'],
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        precision: 0
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Student Status Distribution'
                    }
                },
                animation: {
                    duration: 2000,
                    easing: 'easeInOutQuad'
                }
            }
        });
    });
</script>

{{-- Styling --}}
<style>
    #teacherStatusChart,
    #studentStatusChart {
        max-width: 100%;
        height: auto;
    }

    .btn-gradient-info {
        background: linear-gradient(45deg, #17a2b8, #20c997);
        color: white;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-gradient-info:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(23, 162, 184, 0.5);
    }

    .btn-rounded {
        border-radius: 50px;
        padding: 10px 30px;
    }
</style>
@endsection
