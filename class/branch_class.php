<?php  

class Branch 
{
    public $id;
    public $branch_name;
    public $tel;
    public $prefecture;
    public $city;
    public $address;
    public $building; 

    public function __construct(array $branch)
    {
        $this->id = $branch['id'];
        $this->branch_name = $branch['branch_name'];
        $this->tel = $branch['tel'];
        $this->prefecture = $branch['prefecture'];
        $this->city = $branch['city'];
        $this->address = $branch['address'];
        $this->building = $branch['building'];
    }

    // 住所を繋げて表示

    public function getFullAddress()
    {
       $prefectures = array(
        1 => "北海道", 2 => "宮城県", 3 => "新潟県", 4 => "石川県", 5 => "群馬県", 6 => "栃木県", 7 => "埼玉県",
        8 => "千葉県", 9 => "東京都", 10 => "神奈川県", 11 => "愛知県", 12 => "京都府", 13 => "大阪府", 14 => "兵庫県",
        15 => "奈良県", 16 => "和歌山県", 17 => "鳥取県", 18 => "島根県", 19 => "岡山県", 20 => "広島県",
        21 => "山口県", 22 => "徳島県", 23 => "香川県", 24 => "愛媛県", 25 => "高知県", 26 => "福岡県",
        27 => "佐賀県", 28 => "長崎県", 29 => "熊本県", 30 => "大分県", 31 => "宮崎県", 32 => "鹿児島県",
        33 => "沖縄県", 34 => "岩手県", 35 => "秋田県", 36 => "山形県", 37 => "福島県", 38 => "山梨県",
        39 => "長野県", 40 => "岐阜県", 41 => "静岡県", 42 => "富山県", 43 => "福井県", 44 => "三重県",
        45 => "滋賀県", 46 => "群馬県", 47 => "群馬県"
        );

        return $prefectures[$this->prefecture] . $this->city . $this->address . $this->building;
    }
}
?>