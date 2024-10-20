<?php 
require_once __DIR__ . '/../controll/branch_controll.php'; 
?>

<?php foreach ($branchList as $branch) : ?>
    <tbody>
        <tr>
            <th><?php echo eh($branch->branch_name); ?></th>
            <td data-label="電話番号"><?php echo eh($branch->tel); ?></td>
            <td data-label="住所"><?php echo eh($branch->getFullAddress()); ?></td>
            <td data-label="">
                <a class="edit-btn" href="/employee_site/pages/edit_branch.php?id=<?php echo eh($branch->id); ?>">編集</a>
            </td>
        </tr>
    </tbody>
<?php endforeach; ?>
