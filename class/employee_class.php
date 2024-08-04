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
            return '不明';
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
}

?>