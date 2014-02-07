<div id="filter_bar">

  <h2 class="product_results"><a href="<?php print $link_results;?>">Product Results</a> <span class="count">(<?php print render($total_products); ?>)</span></h2>

  <h3>Narrow Your Search:</h3>

  <div class="toggle_box <?php print isset($browse_markets_css_class) ? $browse_markets_css_class : ''; ?>" id="browse_markets">

    <h4><a href="#" class="toggle_control"><span class="toggle_arrow"></span>Browse by Market</a></h4>

    <?php print render($browse_markets); ?>

  </div>

  <div class="toggle_box <?php print isset($browse_type_css_class) ? $browse_type_css_class : ''; ?>" id="browse_types">

    <h4><a href="#" class="toggle_control"><span class="toggle_arrow"></span>Browse by Type</a></h4>

    <?php print render($browse_type); ?>

  </div>

  <?php

  if(!isset($_GET['search_api_views_fulltext'])){
    if(isset($filters)){
    print  "<h3>Filter By Specification:</h3>";

    for($i=0;$i<count($filters);$i++):
        $id = str_replace(" ","_",strtolower($filters[$i]['content']['#title']));
        $title = $filters[$i]['content']['#title'];
        print sprintf('<div class="toggle_box " id="%s">',$id);
        print sprintf('<h4><a href="#" class="toggle_control"><span class="toggle_arrow"></span>%s</a></h4>',$title);
        print '<div class="toggle_target">';
        print render($filters[$i]);
        print  '</div></div>';
    endfor;

      }
	 }
  ?>


  <?php if($search_page) : ?>
    <h2 class="product_results"><a href="<?php print $link_other_results;?>">Other Results</a> <span class="count">(<?php print render($total_product_assets); ?>)</span></h2>
  <?php endif; ?>

</div>
