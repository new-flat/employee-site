<?php foreach ($data_array as $data) : ?>
    <tbody>
        <tr>
            <th><?php echo eh($data["username"]); ?></th>
            <td data-label="かな"><?php echo eh($data['kana']); ?></td>
            <td data-label="性別">
                <?php
                if ($data["gender"] === 1) {
                    echo "男";
                } elseif ($data["gender"] === 2) {
                    echo "女";
                } else {
                    echo "不明";
                }
                ?>
            </td>
            <td data-label="年齢">
                <?php
                if (isset($data["birth_date"])) {
                    $birthDate = str_replace("-", "", $data["birth_date"]);
                    if (is_numeric($birthDate)) {
                        $currentDate = date('Ymd');
                        $age = floor((int)$currentDate - (int)$birthDate) / 10000;
                        echo eh((int)$age);
                    } else {
                        echo "不明";
                    }
                } else {
                    echo "不明";
                }
                ?>
            </td>
            <td data-label="生年月日"><?php echo isset($data["birth_date"]) ? eh($data['birth_date']) : "不明"; ?></td>
            <td data-label=""><a class="edit-btn" href="edit_employee.php?id=<?php echo eh($data['id']); ?>">編集</a></td>
        </tr>
    </tbody>
<?php endforeach; ?>
