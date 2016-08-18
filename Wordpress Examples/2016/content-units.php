<div class="well">
<h1>Ranged Units</h1>
<?php
if(get_field('ranged_desc'))
{
echo '<p>' . get_field('ranged_desc') . '</p>';
}
?>

</div>

<div class="row">


<?php

if( have_rows('unit') ): ?>
<?php while( have_rows('unit') ): the_row();
// vars
$name = get_sub_field('unit_name');
$detail = get_sub_field('unit_details');
$image = get_sub_field('unit_image');
?>

 <div class="col-sm-12 col-md-12">
    <div class="thumbnail">
      <img src="<?php echo $image; ?>" >
      <div class="caption">
        <h3><?php echo $name; ?></h3>
        <p><?php echo $detail; ?></p>
      </div>
    </div>
  </div>



<?php  endwhile; endif;
//make sure open div is closed

?>
</div>


<div class="well">

<h1>Melee Units</h1>
<?php
if(get_field('melee_desc'))
{
echo '<p>' . get_field('melee_desc') . '</p>';
}
?>

</div>

<div class="row">
<?php

if( have_rows('unit_melee') ): ?>
<?php while( have_rows('unit_melee') ): the_row();
// vars
$name = get_sub_field('unit_name');
$detail = get_sub_field('unit_details');
$image = get_sub_field('unit_image');
?>

 <div class="col-sm-12 col-md-12">
    <div class="thumbnail">
      <img src="<?php echo $image; ?>" >
      <div class="caption">
        <h3><?php echo $name; ?></h3>
        <p><?php echo $detail; ?></p>
      </div>
    </div>
  </div>

<?php
endwhile; endif;
//make sure open div is closed

?>
</div>

<div class="well">

<h1>Siege Units</h1>
<?php
if(get_field('siege_desc'))
{
echo '<p>' . get_field('siege_desc') . '</p>';
}
?>
</div>


<div class="row">
<?php


if( have_rows('unit_siege') ): ?>
<?php while( have_rows('unit_siege') ): the_row();
// vars
$name = get_sub_field('unit_name');
$detail = get_sub_field('unit_details');
$image = get_sub_field('unit_image');
?>

 <div class="col-sm-12 col-md-12">
    <div class="thumbnail">
      <img src="<?php echo $image; ?>" >
      <div class="caption">
        <h3><?php echo $name; ?></h3>
        <p><?php echo $detail; ?></p>
      </div>
    </div>
  </div>

<?php  endwhile; endif;
//make sure open div is closed

?>
</div>
