<?php 
require_once __DIR__ . '/../controll/employee_controll.php'; 
?>
<?php foreach ($employees as $employee) : ?>
    <tbody>
        <tr>
            <th><?php echo eh($employee->username); ?></th>
            <td data-label="かな"><?php echo eh($employee->kana); ?></td>

            <td data-label="支店"><?php echo eh($employee->getAddress() ?? "不明"); ?></td>

            <td data-label="性別"><?php echo eh($employee->genderLabel() ?? "不明"); ?></td>
            
            <td data-label="年齢">
                <?php
                $age = $employee->ageFromBirthday();
                echo eh($age !== null ? (int)$age : "不明");
                ?>
            </td>
            <td data-label="生年月日"><?php echo eh($employee->birth_date ?? "不明"); ?></td>
            <td data-label=""><a href="edit_employee.php?id=<?php echo eh($employee->id); ?>" class="edit-btn">編集</a></td>
            <td data-label=""><a href="detail_view.php?id=<?php echo eh($employee->id); ?>" class="edit-btn">詳細</a></td>
        </tr>
    </tbody>
<?php endforeach; ?>
