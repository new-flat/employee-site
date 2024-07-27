<?php foreach ($data_array as $data) : ?>
    <tbody>
        <tr>
            <th><?php echo eh($data["branch_name"]); ?></th>
            <td data-label="電話番号"><?php echo eh($data['tel']); ?></td>
            <td data-label="住所">
                <?php
                $prefectureName = $prefectures[$data['prefecture']];
                $residence = $prefectureName . $data['city'] . $data['address'];
                if (!empty($data['building'])) {
                    $residence .= ''. $data['building']; 
                }
                echo eh($residence); 
                ?>
            </td>
            <td data-label=""><a class="edit-btn" href="edit_branch.php?id=<?php echo $data['id']; ?>">編集</a></td>
        </tr>
    </tbody>               
<?php endforeach; ?>