<?php 
require_once __DIR__ . '/../controll/employee_controll.php'; 
?>

<?php if ($totalResults > 4) : ?>
    <!-- ◯件中◯-◯件目を表示 -->
    <p><?php echo eh($totalResults); ?>件中<?php echo eh($fromRecord); ?>-<?php echo eh($toRecord); ?>件目を表示</p>

    <!-- 前のページボタン -->
    <?php if ($page > 1) : ?>
        <a class="back_page" href="?<?php echo eh(http_build_query(array_merge($_GET, ['page' => $page - 1]))); ?>"><<</a>
    <?php else : ?>
        <span class="disabled"><<</span>
    <?php endif; ?>

    <!-- ページ番号リンク -->
    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
        <?php if ($i >= $page - $range && $i <= $page + $range) : ?>
            <?php if ($i == $page) : ?>
                <span class="current_page"><?php echo eh($i); ?></span>
            <?php else : ?>
                <a class="page_link" href="?<?php echo eh(http_build_query(array_merge($_GET, ['page' => $i]))); ?>"><?php echo eh($i); ?></a>
            <?php endif; ?>
        <?php endif; ?>
    <?php endfor; ?>

    <!-- 次のページボタン -->
    <?php if ($page < $totalPages) : ?>
        <a class="next_page" href="?<?php echo eh(http_build_query(array_merge($_GET, ['page' => $page + 1]))); ?>">>></a>
    <?php else : ?>
        <span class="disabled">>></span>
    <?php endif; ?>
<?php endif; ?>
