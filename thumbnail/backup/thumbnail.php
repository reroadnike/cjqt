<?php

//添加特色缩略图支持
if ( function_exists('add_theme_support') )add_theme_support('post-thumbnails');

//输出缩略图地址
function Bing_post_thumbnail_src(){
    global $post;
	if( $values = get_post_custom_values("thumb") ) {	//输出自定义域图片地址
		$values = get_post_custom_values("thumb");
		$post_thumbnail_src = $values [0];
	} elseif( has_post_thumbnail() ){    //如果有特色缩略图，则输出缩略图地址
        $thumbnail_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
		$post_thumbnail_src = $thumbnail_src [0];
    } else {
		$post_thumbnail_src = '';
		ob_start();
		ob_end_clean();
		$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
		$post_thumbnail_src = $matches [1] [0];   //获取该图片 src
		if(empty($post_thumbnail_src)){	//如果日志中没有图片，则显示随机图片
			$random = mt_rand(1, 10);
			echo get_bloginfo('template_url');
			echo '/thumbnail/random/'.$random.'.jpg';
			//如果日志中没有图片，则显示默认图片
			//echo '/thumbnail/random/default_thumb.jpg';
		}
	};
	echo $post_thumbnail_src;
}
?>

<?php
//定义缩略图
function Bing_thumbnail($timthumb_w,$timthumb_h){
	?><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>">
<?php if( dopt('Bing_timthumb') ) { ?><img src="<?php bloginfo('template_directory'); ?>/includes/thumbnail/timthumb.php?src=<?php echo Bing_post_thumbnail_src() ?>&h=<?php echo $timthumb_h ?>&w=<?php echo $timthumb_w ?>&zc=1" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" /><?php } else { ?><img src="<?php echo Bing_post_thumbnail_src() ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" /><?php } ?></a>
<?php } ?>