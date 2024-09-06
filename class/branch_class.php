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
        1 => "北海道", 2 => "宮城県", 3 => "埼玉県", 4 => "千葉県", 5 => "東京都", 6 => "神奈川県", 7 => "愛知県",
        8 => "京都府", 9 => "大阪府", 10 => "兵庫県", 11 => "広島県", 12 => "香川県", 13 => "福岡県", 14 => "鹿児島県",
        15 => "沖縄県"
        );

        return $prefectures[$this->prefecture] . $this->city . $this->address . $this->building;
    }
}
?>