
<?php 
require_once __DIR__ . '/../controll/employee_controll.php'; 
?>
<?php foreach ($employees as $employee) : ?>
    <tbody>
        <tr>
            <th><?php echo eh($employee->username); ?></th>
            <td data-label="かな"><?php echo eh($employee->kana); ?></td>

            <td data-label="性別"><?php echo eh($employee->genderLabel()); ?></td>
            
            <td data-label="年齢">
                <?php
                $age = $employee->ageFromBirthday();
                echo eh($age !== null ? (int)$age : "不明");
                ?>
            </td>
            <td data-label="生年月日"><?php echo eh($employee->birth_date ?? "不明"); ?></td>
            <td data-label=""><a class="edit-btn" href="edit_employee.php?id=<?php echo eh($employee->id); ?>">編集</a></td>
        </tr>
    </tbody>
<?php endforeach; ?>
