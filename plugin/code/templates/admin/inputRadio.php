<?=$this->e($title)?><br/>

<input
    type="radio"
    value="0"
    name="<?=$this->e($option_name)?>[<?=$this->e($name)?>]"
    checked=<?=$value ? '': 'checked="checked"'?>
/>

<?=$this->e($false_title)?>

<br/>

<input
    type="radio"
    value="1"
    name="<?=$this->e($option_name)?>[<?=$this->e($name)?>]"
    checked=<?=$value ? 'checked="checked"': '' ?>
/>

<?=$this->e($true_title)?>
