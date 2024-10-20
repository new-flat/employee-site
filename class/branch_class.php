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
        1 => "札幌支店", 2 => "仙台支店", 3 => "さいたま支店", 4 => "東京支店", 5 => "千葉支店", 6 => "横浜支店", 7 => "名古屋支店",
        8 => "京都支店", 9 => "大阪本社", 10 => "神戸支店", 11 => "高松支店", 12 => "京都支店", 13 => "広島支店", 14 => "福岡支店",
        15 => "那覇支店"
        );

        return $prefectures[$this->prefecture] . $this->city . $this->address . $this->building;
    }
}
?>