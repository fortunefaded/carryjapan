<?php echo $this->Form->create();?>
<?php echo $this->Form->input('Qrcode.purchase_from_id', array('label' => '商品の購入元を選択' , 'type' => 'select' , 'options' => $purchase_from));?>
<?php echo $this->Form->input('Qrcode.tracking_number', array('type' => 'text', 'label' => '荷物に付いている追跡番号の下5桁を入力して下さい。', 'placeholder' => '例: 10205'));?>
<?php echo $this->Form->input('Qrcode.price', array('type' => 'text', 'label' => '商品の金額を入力して下さい。', 'placeholder' => '例: 1200'));?>
<?php echo $this->Form->input('Qrcode.package_category_id', array('label' => '商品のカテゴリを選択' , 'type' => 'select' , 'options' => $category));?>
<?php echo $this->Form->input('Qrcode.weight', array('type' => 'text', 'label' => '荷物の重量をグラムで入力して下さい。', 'placeholder' => '例: 1500'));?>
<?php echo $this->Form->input('Qrcode.is_combine', array('type' => 'select', 'label' => '商品の同梱', 'options' => array(0 => '同梱可能', 1 => '同梱不可')));?>
<?php echo $this->Form->end('登録する');?>