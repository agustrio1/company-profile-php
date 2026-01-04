<!-- File: resources/views/partials/home/team.php -->
<section class="py-12 md:py-16 bg-gray-50">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="text-center mb-8 md:mb-12">
            <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-900 mb-3">Tim Konsultan Kami</h2>
            <p class="text-base md:text-lg text-gray-600 max-w-2xl mx-auto">
                Kenalan dengan tim ahli profesional yang siap membantu kesuksesan bisnis Anda
            </p>
        </div>

        <?php 
        $allEmployees = [];
        if (!empty($teams)) {
            foreach ($teams as $team) {
                if (!empty($team->employees)) {
                    $allEmployees = array_merge($allEmployees, $team->employees);
                }
            }
        }
        ?>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
            <?php if (!empty($allEmployees)): ?>
                <?php foreach (array_slice($allEmployees, 0, 4) as $employee): ?>
                    <?php
                    if (is_array($employee)) {
                        $employee = (object) $employee;
                    }
                    
                    $employeeName = $employee->name ?? 'Staff Member';
                    $nameParts = explode(' ', $employeeName);
                    $initials = strtoupper(substr($nameParts[0], 0, 1));
                    if (isset($nameParts[1])) {
                        $initials .= strtoupper(substr($nameParts[1], 0, 1));
                    }
                    
                    $photoUrl = null;
                    if (!empty($employee->photo)) {
                        $photoUrl = method_exists($employee, 'getPhotoUrl') 
                            ? $employee->getPhotoUrl() 
                            : asset('uploads/' . $employee->photo);
                    }
                    ?>
                    <div class="text-center">
                        <div class="mb-3 md:mb-4 flex justify-center">
                            <?php if ($photoUrl): ?>
                                <img class="w-20 h-20 md:w-24 md:h-24 lg:w-28 lg:h-28 rounded-full ring-2 ring-gray-200 object-cover" 
                                     src="<?= htmlspecialchars($photoUrl) ?>" 
                                     alt="<?= htmlspecialchars($employeeName) ?>"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="w-20 h-20 md:w-24 md:h-24 lg:w-28 lg:h-28 rounded-full bg-blue-600 flex items-center justify-center ring-2 ring-gray-200" style="display:none;">
                                    <span class="text-2xl md:text-3xl lg:text-4xl font-bold text-white"><?= $initials ?></span>
                                </div>
                            <?php else: ?>
                                <div class="w-20 h-20 md:w-24 md:h-24 lg:w-28 lg:h-28 rounded-full bg-blue-600 flex items-center justify-center ring-2 ring-gray-200">
                                    <span class="text-2xl md:text-3xl lg:text-4xl font-bold text-white"><?= $initials ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <h3 class="text-sm md:text-base lg:text-lg font-semibold text-gray-900 mb-1">
                            <?= htmlspecialchars($employeeName) ?>
                        </h3>
                        <p class="text-xs md:text-sm text-blue-600 font-medium">
                            <?= htmlspecialchars($employee->position ?? 'Staff') ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <?php for ($i = 0; $i < 4; $i++): ?>
                    <div class="text-center">
                        <div class="mb-3 md:mb-4 flex justify-center">
                            <div class="w-20 h-20 md:w-24 md:h-24 lg:w-28 lg:h-28 rounded-full bg-blue-600 flex items-center justify-center ring-2 ring-gray-200">
                                <svg class="w-10 h-10 md:w-12 md:h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-sm md:text-base lg:text-lg font-semibold text-gray-900 mb-1">Staff Member</h3>
                        <p class="text-xs md:text-sm text-blue-600 font-medium">Konsultan Bisnis</p>
                    </div>
                <?php endfor; ?>
            <?php endif; ?>
        </div>
    </div>
</section>