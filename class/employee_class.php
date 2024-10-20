<?php

class Employee 
{
    public $id;
    public $username;
    public $kana;
    public $branch;
    public $gender;
    public $birth_date;
    public $email;
    public $commute_time;
    public $blood_type;
    public $married;
    public $image;
    public $intro_text;

    public function __construct(array $employee)
    {
        $this->id = $employee['id'];
        $this->username = $employee['username'];
        $this->kana = $employee['kana'];
        $this->branch = $employee['branch'];
        $this->gender = $employee['gender'];
        $this->birth_date = $employee['birth_date'];
        $this->email = $employee['email'];
        $this->commute_time = $employee['commute_time'];
        $this->blood_type = $employee['blood_type'];
        $this->married = $employee['married'];
        $this->married = $employee['image'];
        $this->married = $employee['intro_text'];
    }

    // 生年月日から年齢を算出
    public function ageFromBirthday(): ?float
    {
        if ($this->birth_date === null) {
            return null;
        }

        // 現在の日付を取得
        $currentDate = date('Ymd');
        // 生年月日を処理
        $birthDate = str_replace("-", "", $this->birth_date);
        // 年齢を計算
        $age = floor((int)$currentDate - (int)$birthDate) / 10000;
        if ($age === false) {
            return null;
        }

        return $age;
    }

    // 性別ラベル表示
    public function genderLabel(): ?string 
    {
        if ($this->gender === null) {
            return null;
        }
    
        if ($this->gender === 1) {
            return '男';
        }
    
        if ($this->gender === 2) {
            return '女';
        }
    
        return '不明';
    }

    // 血液型ラベル表示
    public function bloodTypeLabel():string
    {
        switch ($this->blood_type) {
            case 1:
                return "A型";
                break;
            case 2:
                return "B型";
                break;
            case 3:
                return "O型";
            case 4:
                return "AB型";
                break;
            case 5:
                return "不明";
                break;
            default:
                return "";
        }
    }

    // 都道府県コードから地名を表示
    public function getAddress()
    {
        $branches = array(
            1 => "札幌支店", 2 => "仙台支店", 3 => "さいたま支店", 4 => "東京支店", 5 => "千葉支店", 6 => "横浜支店", 7 => "名古屋支店",
            8 => "京都支店", 9 => "大阪本社", 10 => "神戸支店", 11 => "高松支店", 12 => "広島支店", 13 => "福岡支店",14 => "那覇支店"
        );


        // 空の支店キーをチェック
        if (empty($this->branch) || !isset($branches[$this->branch])) {
            return ''; // 空欄を返す
        }

        return $branches[$this->branch];
    }

}

?>