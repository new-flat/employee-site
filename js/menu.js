$(function () {
  $('.menu-parent_a').click(function (e) {
    // aタグのデフォルト動作を無効化
    e.preventDefault();

    // メガメニュー（子要素）をスライドダウンまたはアップ
    $(this).siblings('.menu-child').slideToggle(300);
    
    // メニューにactiveクラスを付与     
    $(this).addClass('active');
    
    // 他のメニューをスライドアップして非表示にする  
    $('.menu-parent_a').not($(this)).siblings('.menu-child').slideUp(100);
    
    // 他のメニューからactiveクラスを取り除く 
    $('.menu-parent_a').not($(this)).removeClass('active');
  });

  // メニュー内でクリックされたときはイベントを伝播しない
  $('.menu-child').click(function(e) {
    e.stopPropagation();
  });

  // メガメニュー以外の場所をクリックしたらメニューを閉じる
  $(document).on('click touchend', function (e) {
    if (!$(e.target).closest('.menu-parent').length) {
      $('.menu-parent_a').removeClass('active');
      $('.menu-child').slideUp(100);
    }
  });
});
