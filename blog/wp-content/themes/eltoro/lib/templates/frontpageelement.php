<div class="element-container">
	<input type="hidden" name="front_page[elements][<?php echo $this -> id;?>][id]" value="<?php echo $this -> id;?>" class="element-id">
	<div class="on-overview">
		<header>
			<span class="title fpb">
				<a href="javascript:void(0);" class="fpb_icon">&nbsp;</a>
				<a href="javascript:void(0);" class="fpb_label">
					<?php echo $this -> name; ?>
				</a>
			</span>
			<span class="fr">
				<span class="fpb edit button">
					<a href="javascript:void(0);" class="fpb_icon">&nbsp;</a>
					<a href="javascript:void(0);" class="fpb_label">
						<?php echo __( 'Edit' , 'cosmotheme' );?>
					</a>
				</span>
				<span class="fpb delete button">
					<a href="javascript:void(0);" class="fpb_icon">&nbsp;</a>
					<a href="javascript:void(0);" class="fpb_label">
						<?php echo __( 'Delete' , 'cosmotheme' );?>
					</a>
				</span>
			</span>
		</header>
        <p class="element-description">
        </p>
		<div class="undo-container">
			<?php echo __( 'If you change your mind, you have' , 'cosmotheme' );?> <span class="countdown">5</span> <?php echo __( 'seconds to' , 'cosmotheme' );?> <a href="javascript:void(0);" class="undo"><?php echo __( 'Undo' , 'cosmotheme' );?></a>.
		</div>
	</div>
	<div class="on-edit">
		<header>
			<span class="title fpb add_fpb">
				<a href="javascript:void(0);" class="fpb_icon">&nbsp;</a>
				<a href="javascript:void(0);" class="fpb_label">
					<?php echo $this -> name; ?>
				</a>
			</span>
			<span class="fr">
				<span class="fpb preview button">
					<a href="javascript:void(0);" class="fpb_icon">&nbsp;</a>
					<a href="javascript:void(0);" class="fpb_label">
						<?php echo __( 'Toggle preview' , 'cosmotheme' );?>
					</a>
				</span>
				<span class="fpb apply button">
					<a href="javascript:void(0);" class="fpb_icon">&nbsp;</a>
					<a href="javascript:void(0);" class="fpb_label">
						<?php echo __( 'Save' , 'cosmotheme' );?>
					</a>
				</span>
				<span class="fpb discard button">
					<a href="javascript:void(0);" class="fpb_icon">&nbsp;</a>
					<a href="javascript:void(0);" class="fpb_label">
						<?php echo __( 'Discard' , 'cosmotheme' );?>
					</a>
				</span>
			</span>
		</header>
        <div class="iframe-wrapper">
            <iframe class="preview-iframe" name="iframe_<?php echo $this -> id;?>"></iframe>
        </div>
		<div class="panel the-settings">
			<div class="widgets fly-left">
				<div class="content">
					<h1>
						<?php echo __( 'So you want to add a widget zone?' , 'cosmotheme' ); ?>
					</h1>
					<ol>
						<li><?php echo __( 'First of all, we recomend you give a name to this element' , 'cosmotheme' );?></li>
						<li>
							<?php echo __( 'After you named your frontpage element, click' , 'cosmotheme' );?>
							<b>
								<?php echo __( 'Save', 'cosmotheme' );?>
							</b>
							<?php echo __( 'at the top of this box' , 'cosmotheme' );?></li>
						<li><?php echo __( 'Then you can sort your frontpage elements' , 'cosmotheme' );?></li>
						<li>
							<?php echo __( "And don't forget to click" , 'cosmotheme' );?>
							<b>
						 		<?php echo __( 'Update settings' , 'cosmotheme' );?>
					 		</b>
					 		<?php echo __( 'at the bottom of the options page' , 'cosmotheme' );?></li>
						<li>
							<?php echo __( "Now go to " , 'cosmotheme' );?>
							<a href="<?php echo admin_url( 'widgets.php' );?>"><?php echo __( 'Appearence -> Widgets' , 'cosmotheme' );?></a>
							<?php echo __( 'Where you can see your newly created widget zone an drag widgets on it just like you would normally do' , 'cosmotheme' );?>
						</li>
					</ol>
				</div>
			</div>
			<div class="categories taxonomies fly-left">
                <div class="search-holder">
                    <input class="search">
                    <a href="javascript:void(0);" class="clear-input" title="<?php echo __( 'Clear input' , 'cosmotheme' );?>"></a>
                    <a href="javascript:void(0);" class="back-to-top" title="<?php echo __( 'Back to top' , 'cosmotheme' );?>"></a>
                </div>
				<div class="content">
					<?php
						$this -> list_categories();
					?>
				</div>
			</div>
			<div class="tags taxonomies fly-left">
                <div class="search-holder">
                    <input class="search">
                    <a href="javascript:void(0);" class="clear-input" title="<?php echo __( 'Clear input' , 'cosmotheme' );?>"></a>
                    <a href="javascript:void(0);" class="back-to-top" title="<?php echo __( 'Back to top' , 'cosmotheme' );?>"></a>
                </div>
				<div class="content">
					<?php
						$this -> list_tags();
					?>
				</div>
			</div>
			<div class="portfolios taxonomies fly-left">
                <div class="search-holder">
                    <input class="search">
                    <a href="javascript:void(0);" class="clear-input" title="<?php echo __( 'Clear input' , 'cosmotheme' );?>"></a>
                    <a href="javascript:void(0);" class="back-to-top" title="<?php echo __( 'Back to top' , 'cosmotheme' );?>"></a>
                </div>
				<div class="content">
					<?php
						$this -> list_portfolios();
					?>
				</div>
			</div>
            <div class="pages taxonomies fly-left">
                <div class="search-holder">
                    <input class="search">
                    <a href="javascript:void(0);" class="clear-input" title="<?php echo __( 'Clear input' , 'cosmotheme' );?>"></a>
                    <a href="javascript:void(0);" class="back-to-top" title="<?php echo __( 'Back to top' , 'cosmotheme' );?>"></a>
                </div>
                <div class="content">
                    <?php
                        $this -> list_pages();
                    ?>
                </div>
            </div>
            <div class="posts taxonomies fly-left">
                <div class="search-holder">
                    <input type="hidden" name="" class="generic-record generic-value" value="" />
                    <input type="hidden" class="generic-params" value="%7B%22post_type%22%3A%22post%22%2C%22post_status%22%3A%22publish%22%7D" />
                    <input class="search generic-record-search" value="" onchange="javascript:act.search( this , '-');">
                    <a href="javascript:void(0);" class="clear-input" title="<?php echo __( 'Clear input' , 'cosmotheme' );?>"></a>
                    <a href="javascript:void(0);" class="back-to-top" title="<?php echo __( 'Back to top' , 'cosmotheme' );?>"></a>
                </div>
                <div class="content">
                    <?php
                        $this -> list_posts();
                    ?>
                </div>
            </div>
			<div class="standard-generic-field generic-field-front_page">
				<div class="generic-label">
					<label>
						<?php echo __( 'Element label' , 'cosmotheme' );?>
					</label>
				</div>
				<div class="generic-field generic-field-input">
					<input name="front_page[elements][<?php echo $this -> id;?>][name]" class="element-title" value="<?php echo $this -> name;?>">
				</div>
				<div class="clear"></div>
			</div>

            <div class="standard-generic-field generic-field-header">
                <div class="generic-label">
                    <label>
                        <?php echo __( 'Display this label in front-end', 'cosmotheme' );?>
                    </label>
                </div>
                <div class="generic-field generic-field-image-select">
                    <label for="<?php echo $this -> id;?>_show_title_yes">
                        <?php echo __( 'Yes' , 'cosmotheme' );?>
                        <input type="radio" value="yes" id="<?php echo $this -> id;?>_show_title_yes" name="front_page[elements][<?php echo $this -> id;?>][show_title]" <?php checked( $this -> show_title, 'yes' );?>>
                    </label>
                    <label for="<?php echo $this -> id;?>_show_title_no">
                        <?php echo __( 'No' , 'cosmotheme' );?>
                        <input type="radio" value="no" id="<?php echo $this -> id;?>_show_title_no" name="front_page[elements][<?php echo $this -> id;?>][show_title]" <?php checked( $this -> show_title, 'no' );?>>
                    </label>
                </div>
            </div>

			<div class="standard-generic-field generic-field-header">
				<div class="generic-label">
					<label>
						<?php echo __( 'Choose element type', 'cosmotheme' );?>
					</label>
				</div>
				<div class="generic-field generic-field-image-select element-type">                            
					<label for="<?php echo $this -> id;?>_type_category">
						<?php echo __( 'Categories' , 'cosmotheme' );?>
                        <input type="radio" value="category" id="<?php echo $this -> id;?>_type_category" name="front_page[elements][<?php echo $this -> id;?>][type]" class="category-type" <?php checked( $this -> type, 'category' );?>>
                    </label>
					<label for="<?php echo $this -> id;?>_type_tags">
						<?php echo __( 'Tags' , 'cosmotheme' );?>
                        <input type="radio" value="tag" id="<?php echo $this -> id;?>_type_tags" name="front_page[elements][<?php echo $this -> id;?>][type]" class="tag-type" <?php checked( $this -> type, 'tag' );?>>
                    </label>
					<label for="<?php echo $this -> id;?>_type_portfolio">
						<?php echo __( 'Portfolios' , 'cosmotheme' );?>
                        <input type="radio" value="portfolio" id="<?php echo $this -> id;?>_type_portfolio" name="front_page[elements][<?php echo $this -> id;?>][type]" class="portfolio-type" <?php checked( $this -> type, 'portfolio' );?>>
                    </label>
                    <?php if( options::logic( 'general', 'enb_likes' ) ){?>
                        <label for="<?php echo $this -> id;?>_type_featured">
                            <?php echo __( 'Featured posts' , 'cosmotheme' );?>
                            <input type="radio" value="featured" id="<?php echo $this -> id;?>_type_featured" name="front_page[elements][<?php echo $this -> id;?>][type]" <?php checked( $this -> type, 'featured' );?>>
                        </label>
                    <?php } ?>
					<label for="<?php echo $this -> id;?>_type_latest">
						<?php echo __( 'Latest posts' , 'cosmotheme' );?>
                        <input type="radio" value="latest" id="<?php echo $this -> id;?>_type_latest" name="front_page[elements][<?php echo $this -> id;?>][type]" <?php checked( $this -> type, 'latest' );?>>
                    </label>
					<label for="<?php echo $this -> id;?>_type_page">
						<?php echo __( 'Page' , 'cosmotheme' );?>
                        <input  class="page-type" type="radio" value="page" id="<?php echo $this -> id;?>_type_page" name="front_page[elements][<?php echo $this -> id;?>][type]" class="page-type" <?php checked( $this -> type, 'page' );?>>
                    </label>
					<label for="<?php echo $this -> id;?>_type_post">
						<?php echo __( 'Post' , 'cosmotheme' );?>
                        <input class="post-type" type="radio" value="post" id="<?php echo $this -> id;?>_type_post" name="front_page[elements][<?php echo $this -> id;?>][type]" class="post-type" <?php checked( $this -> type, 'post' );?>>
                    </label>
					<label for="<?php echo $this -> id;?>_type_widget">
						<?php echo __( 'Widgets' , 'cosmotheme' );?>
                        <input type="radio" value="widget_zone" id="<?php echo $this -> id;?>_type_widget" name="front_page[elements][<?php echo $this -> id;?>][type]" class="widget-type" <?php checked( $this -> type, 'widget_zone' );?>>
                    </label>
                </div>
            </div>

            <div class="standard-generic-field generic-field-header options-view-type">
                <div class="generic-label">
                    <label>
                        <?php echo __( 'Select view type', 'cosmotheme' );?>
                    </label>
                </div>
                <div class="generic-field generic-field-image-select element-view-type">
                    <label for="<?php echo $this -> id;?>_view_list">
                        <?php echo __( 'List view' , 'cosmotheme' );?>
                        <input class="list_view" type="radio" value="list_view" id="<?php echo $this -> id;?>_view_list" name="front_page[elements][<?php echo $this -> id;?>][view]" <?php checked( $this -> view, 'list_view' );?>>
                    </label>
                    <label for="<?php echo $this -> id;?>_view_grid">
                        <?php echo __( 'Grid view' , 'cosmotheme' );?>
                        <input class="grid_view" type="radio" value="grid_view" id="<?php echo $this -> id;?>_view_grid" name="front_page[elements][<?php echo $this -> id;?>][view]" <?php checked( $this -> view, 'grid_view' );?>>
                    </label>
                    <label for="<?php echo $this -> id;?>_view_thumb">
                        <?php echo __( 'Thumbnail view' , 'cosmotheme' );?>
                        <input class="thumbnail_view" type="radio" value="grid_view_thumbnails" id="<?php echo $this -> id;?>_view_thumb" name="front_page[elements][<?php echo $this -> id;?>][view]" <?php checked( $this -> view, 'grid_view_thumbnails' );?>>
                    </label>
                </div>
            </div>

            <div class="standard-generic-field generic-field-header options-columns">
                <div class="generic-label">
                    <label>
                        <?php echo __( 'Number of columns', 'cosmotheme' );?>
                    </label>
                </div>
                <div class="generic-field generic-field-image-select">
                    <label for="<?php echo $this -> id;?>_columns_2" class="columns-2">
                        <?php echo __( 'Two' , 'cosmotheme' );?>
                        <input type="radio" value="2" id="<?php echo $this -> id;?>_columns_2" name="front_page[elements][<?php echo $this -> id;?>][columns]" <?php checked( $this -> columns, 2 );?>>
                    </label>
                    <label for="<?php echo $this -> id;?>_columns_3" class="columns-3">
                        <?php echo __( 'Three' , 'cosmotheme' );?>
                        <input type="radio" value="3" id="<?php echo $this -> id;?>_columns_3" name="front_page[elements][<?php echo $this -> id;?>][columns]" <?php checked( $this -> columns, 3 );?>>
                    </label>
                    <label for="<?php echo $this -> id;?>_columns_4" class="columns-4">
                        <?php echo __( 'Four' , 'cosmotheme' );?>
                        <input type="radio" value="4" id="<?php echo $this -> id;?>_columns_4" name="front_page[elements][<?php echo $this -> id;?>][columns]" <?php checked( $this -> columns, 4 );?>>
                    </label>
                    <label for="<?php echo $this -> id;?>_columns_6" class="columns-6">
                        <?php echo __( 'Six' , 'cosmotheme' );?>
                        <input type="radio" value="6" id="<?php echo $this -> id;?>_columns_6" name="front_page[elements][<?php echo $this -> id;?>][columns]" <?php checked( $this -> columns, 6 );?>>
                    </label>
                    <label for="<?php echo $this -> id;?>_columns_9" class="columns-9">
                        <?php echo __( 'Nine' , 'cosmotheme' );?>
                        <input type="radio" value="9" id="<?php echo $this -> id;?>_columns_9" name="front_page[elements][<?php echo $this -> id;?>][columns]" <?php checked( $this -> columns, 9 );?>>
                    </label>
                </div>
            </div>

            <div class="standard-generic-field generic-field-header option-numberposts">
                <div class="generic-label">
                    <label>
                        <?php echo __( 'Number of posts', 'cosmotheme' );?>
                    </label>
                </div>
                <div class="generic-field">
                    <input name="front_page[elements][<?php echo $this -> id;?>][numberposts]" value="<?php echo $this -> numberposts;?>">
                </div>
                <div class="hint"><?php echo __( 'Please select a number that is divisible by the number of columns', 'cosmothemes' );?>.</div>
            </div>

            <div class="standard-generic-field generic-field-header enb-list-thumbs-container">
                <div class="generic-label">
                    <label>
                        <?php echo __( 'Display gallery type', 'cosmotheme' );?>
                    </label>
                </div>
                <div class="generic-field generic-field-image-select">
                    <label for="<?php echo $this -> id;?>_enb_list_thumbs_yes">
                        <?php echo __( 'Yes' , 'cosmotheme' );?>
                        <input type="radio" value="yes" id="<?php echo $this -> id;?>_enb_list_thumbs_yes" name="front_page[elements][<?php echo $this -> id;?>][list_thumbs]" <?php checked( $this -> list_thumbs, 'yes' );?>>
                    </label>
                    <label for="<?php echo $this -> id;?>_enb_list_thumbs_no">
                        <?php echo __( 'No' , 'cosmotheme' );?>
                        <input type="radio" value="no" id="<?php echo $this -> id;?>_enb_list_thumbs_no" name="front_page[elements][<?php echo $this -> id;?>][list_thumbs]" <?php checked( $this -> list_thumbs, 'no' );?>>
                    </label>
                </div>
            </div>

            <div class="standard-generic-field generic-field-header enb-carousel-container">
                <div class="generic-label">
                    <label>
                        <?php echo __( 'Enable carousel', 'cosmotheme' );?>
                    </label>
                </div>
                <div class="generic-field generic-field-image-select">
                    <label for="<?php echo $this -> id;?>_enb_carousel_yes">
                        <?php echo __( 'Yes' , 'cosmotheme' );?>
                        <input type="radio" value="yes" id="<?php echo $this -> id;?>_enb_carousel_yes" name="front_page[elements][<?php echo $this -> id;?>][carousel]" <?php checked( $this -> carousel, 'yes' );?>>
                    </label>
                    <label for="<?php echo $this -> id;?>_enb_carousel_no">
                        <?php echo __( 'No' , 'cosmotheme' );?>
                        <input type="radio" value="no" id="<?php echo $this -> id;?>_enb_carousel_no" name="front_page[elements][<?php echo $this -> id;?>][carousel]" <?php checked( $this -> carousel, 'no' );?>>
                    </label>
                </div>
            </div>

            <div class="standard-generic-field generic-field-header options-pagination">
                <div class="generic-label">
                    <label>
                        <?php echo __( 'Enable pagination', 'cosmotheme' );?>
                    </label>
                </div>
                <div class="generic-field generic-field-image-select">
                    <label for="<?php echo $this -> id;?>_enb_pagination_yes">
                        <?php echo __( 'Yes' , 'cosmotheme' );?>
                        <input type="radio" value="yes" id="<?php echo $this -> id;?>_enb_pagination_yes" name="front_page[elements][<?php echo $this -> id;?>][pagination]" <?php checked( $this -> pagination, 'yes' );?>>
                    </label>
                    <label for="<?php echo $this -> id;?>_enb_pagination_no">
                        <?php echo __( 'No' , 'cosmotheme' );?>
                        <input type="radio" value="no" id="<?php echo $this -> id;?>_enb_pagination_no" name="front_page[elements][<?php echo $this -> id;?>][pagination]" <?php checked( $this -> pagination, 'no' );?>>
                    </label>
                </div>
            </div>

            <div class="standard-generic-field generic-field-header options-load-more">
                <div class="generic-label">
                    <label>
                        <?php echo __( 'Show "Load more"', 'cosmotheme' );?>
                    </label>
                </div>
                <div class="generic-field generic-field-image-select">
                    <label for="<?php echo $this -> id;?>_enb_load_more_yes">
                        <?php echo __( 'Yes' , 'cosmotheme' );?>
                        <input type="radio" value="yes" id="<?php echo $this -> id;?>_enb_load_more_yes" name="front_page[elements][<?php echo $this -> id;?>][load_more]" <?php checked( $this -> load_more, 'yes' );?>>
                    </label>
                    <label for="<?php echo $this -> id;?>_enb_load_more_no">
                        <?php echo __( 'No' , 'cosmotheme' );?>
                        <input type="radio" value="no" id="<?php echo $this -> id;?>_enb_load_more_no" name="front_page[elements][<?php echo $this -> id;?>][load_more]" <?php checked( $this -> load_more, 'no' );?>>
                    </label>
                </div>
            </div>
		</div>
	</div>
</div>