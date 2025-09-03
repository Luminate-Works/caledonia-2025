<?php
    $id = (!empty($block['anchor'])) ? $block['anchor'] : 'block-'.$block['id'];
    $className = 'top-investment-table';
    if (!empty($block['className'])) $className .= ' '.$block['className'];
    if (!empty($block['align'])) $className .= ' align'.$block['align'];
?>

<div class="<?= esc_attr($className); ?>">
  <?php if( have_rows('list') ): ?>
    <table>
      <thead>
        <tr>
          <th class="name">Name</th>
          <th class="pool"><p>Pool</p></th>
          <th class="geography"><p>Geography<sup>1</sup></p></th>
          <th class="business"><p>Business</p></th>
          <th class="value"><p>Value (£m)</p></th>
          <th class="net_assets"><p>Net assets (%)</p></th>
        </tr>
      </thead>
      <tbody>
        <?php while( have_rows('list') ) : the_row(); 
            $icon = get_sub_field('icon');
            $name = get_sub_field('name');
            $pool = get_sub_field('pool');
            $geography = get_sub_field('geography');
            $business = get_sub_field('business');
            $value = get_sub_field('value');
            $net_assets = get_sub_field('net_assets');
            $border_color = get_sub_field('border_color');
        ?>
          <tr>
            <td class="name">
              <div class="icon-wrapper">
                <img class="icon" src="<?php echo esc_url($icon); ?>" alt="<?php echo esc_attr($name); ?>">
                <?php echo esc_html($name); ?>
              </div>
            </td>
            <td class="pool"><p style="border-color:<?= $border_color?>  !important;"><?php echo esc_html($pool); ?></p></td>
            <td class="geography"><p><?php echo esc_html($geography); ?></p></td>
            <td class="business"><p><?php echo esc_html($business); ?></p></td>
            <td class="value"><p><?php echo esc_html($value); ?></p></td>
            <td class="net_assets"><p><?php echo esc_html($net_assets); ?></p></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
