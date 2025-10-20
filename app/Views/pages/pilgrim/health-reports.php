<?php
$pageTitle = __('health_reports');
$currentLang = getCurrentLanguage();
?>

<div class="relative z-10">
    <div class="flex flex-wrap items-start">
        <!-- Page Header -->
        <div class="w-full mb-6">
            <h1 class="text-3xl font-bold text-primary mb-2"><?= __('my_medical_reports') ?></h1>
            <p style="color: var(--text-secondary);"><?= __('view_reports') ?></p>
        </div>
        
        <!-- Health Reports -->
        <div class="w-full">
            <?php if (!empty($healthReports)): ?>
                <div class="space-y-4">
                    <?php foreach ($healthReports as $report): ?>
                        <div class="card shadow-lg" style="background-color: var(--bg-secondary);">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-xl font-semibold text-primary mb-2">
                                        <?= getLocalizedField($report, 'diagnosis') ?: __('medical_report') ?>
                                    </h3>
                                    <div class="flex items-center space-x-4 space-x-reverse text-sm" style="color: var(--text-secondary);">
                                        <span><i class="fas fa-calendar <?= $currentLang === 'ar' ? 'ml-2' : 'mr-2' ?>"></i><?= formatLocalizedDateTime($report['report_date']) ?></span>
                                        <?php if ($report['doctor_name']): ?>
                                            <span><i class="fas fa-user-md <?= $currentLang === 'ar' ? 'ml-2' : 'mr-2' ?>"></i><?= __('dr') ?>. <?= htmlspecialchars($report['doctor_name'] . ' ' . $report['doctor_last_name']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                    <?= __('medical_report') ?>
                                </span>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Diagnosis -->
                                <?php $diagnosis = getLocalizedField($report, 'diagnosis'); ?>
                                <?php if ($diagnosis): ?>
                                    <div>
                                        <h4 class="font-semibold text-primary mb-2">
                                            <i class="fas fa-stethoscope <?= $currentLang === 'ar' ? 'ml-2' : 'mr-2' ?>"></i><?= __('diagnosis') ?>
                                        </h4>
                                        <div class="p-3 rounded" style="background-color: var(--bg-primary);">
                                            <p><?= nl2br(htmlspecialchars($diagnosis)) ?></p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Treatment -->
                                <?php $treatment = getLocalizedField($report, 'treatment'); ?>
                                <?php if ($treatment): ?>
                                    <div>
                                        <h4 class="font-semibold text-primary mb-2">
                                            <i class="fas fa-procedures <?= $currentLang === 'ar' ? 'ml-2' : 'mr-2' ?>"></i><?= __('treatment') ?>
                                        </h4>
                                        <div class="p-3 rounded" style="background-color: var(--bg-primary);">
                                            <p><?= nl2br(htmlspecialchars($treatment)) ?></p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Medications -->
                                <?php $medications = getLocalizedField($report, 'medications_prescribed'); ?>
                                <?php if ($medications): ?>
                                    <div>
                                        <h4 class="font-semibold text-primary mb-2">
                                            <i class="fas fa-pills <?= $currentLang === 'ar' ? 'ml-2' : 'mr-2' ?>"></i><?= __('medications') ?>
                                        </h4>
                                        <div class="p-3 rounded" style="background-color: var(--bg-primary);">
                                            <p><?= nl2br(htmlspecialchars($medications)) ?></p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Follow-up Notes -->
                                <?php $followUpNotes = getLocalizedField($report, 'follow_up_notes'); ?>
                                <?php if ($followUpNotes): ?>
                                    <div>
                                        <h4 class="font-semibold text-primary mb-2">
                                            <i class="fas fa-clipboard-check <?= $currentLang === 'ar' ? 'ml-2' : 'mr-2' ?>"></i><?= __('follow_up_notes') ?>
                                        </h4>
                                        <div class="p-3 rounded" style="background-color: var(--bg-primary);">
                                            <p><?= nl2br(htmlspecialchars($followUpNotes)) ?></p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="card shadow-lg" style="background-color: var(--bg-secondary);">
                    <div class="text-center py-12" style="color: var(--text-secondary);">
                        <i class="fas fa-file-medical text-6xl mb-4"></i>
                        <h3 class="text-xl font-semibold mb-2"><?= __('no_reports_available') ?></h3>
                        <p class="mb-6"><?= __('no_medical_reports_created') ?></p>
                        <a href="<?= url('/pilgrim/medical-requests') ?>" class="btn-primary">
                            <i class="fas fa-plus <?= $currentLang === 'ar' ? 'ml-2' : 'mr-2' ?>"></i>
                            <?= __('request_medical_consultation') ?>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
