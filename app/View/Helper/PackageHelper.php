<?php
class PackageHelper extends AppHelper {
	public function getStatus($num = null){
		return $num == 0 ? '現在梱包中のお荷物です。' : '梱包が完了したお荷物です。';
	}
}
?>