<div class="menu-search">
		<ul>
		<?if($g_sess->get_var("systemTeam") == "admin"){?> 
			<li><a href="#">Manage</a>
            <ul>
            <li><a href="source-manager.php">Sources</a></li> 
			<li><a href="products-manager.php">Products</a></li>
			<li><a href="user-manager.php">Users</a></li>
            </ul>
            </li>
            <li><a href="#">Manager Tools</a>
            <ul>
            <li><a href="source-types.php">Source Types</a></li>
            <li><a href="inspiration-manager.php">Inspiration Manager</a></li>
            <li><a href="categories.php">Category Manager</a></li>
            <li><a href="style-manager.php">Style Manager</a></li>
            <li><a href="color-manager.php">Color Manager</a></li>
            <li><a href="genre-manager.php">Genre Manager</a></li>
            </ul>
            
            <li><a href="#">Web Site Pages</a>
            <ul>
            <li><a href="inspiration-pages.php">Inspiration Pages</a></li>
            <li><a href="home-page.php">Home Page</a></li>
            <li><a href="happenings-pages.php">Happenings</a></li>
            <li><a href="featured-source.php">Featured Source</a></li>
            </ul>
			<!-- <li><a href="billing-manager.php">Billing</a></li>
			<li><a href="#">Help</a>
            	<ul id="helpMenu">
                	<li><a href="help-manager.php">Help Manager</a></li>
                    <li><a href="faqs.php">FAQs</a></li>
                </ul>
        	</li> 
		<?}?>
		<?if($g_sess->get_var("systemTeam") == "retailer"){?>
			<li><a href="retailer-home.php">My Products</a>
            	<ul>
            		<li><a rel="Add Product" href="ajax-product-add.php" class="modalopen">Add Products</a></li>
            		<li><a href="retailer-home.php">Manage My Products</a></li>
            	</ul>
            </li>
            <li><a href="myStore.php">My Store</a></li>
            <li><a href="#">Help</a>
            	<ul id="helpMenu">
                	<li><a href="help-manager.php">Help Manager</a></li>
                    <li><a href="faqs.php">FAQs</a></li>
                </ul>
        	</li>
		<?}?>
		<?if($g_sess->get_var("systemTeam") == "photographer"){?>
			<li><a href="products-manager.php">Products</a></li>
			<li><a href="#">Help</a>
            	<ul id="helpMenu">
                	<li><a href="help-manager.php">Help Manager</a></li>
                    <li><a href="faqs.php">FAQs</a></li>
                </ul>
        	</li>
		<?}?>
		<?if($g_sess->get_var("systemTeam") == "manufacturer"){?>
			<li><a href="products-manager.php">Products</a></li>
			<li><a href="#">Help</a>
            	<ul id="helpMenu">
                	<li><a href="help-manager.php">Help Manager</a></li>
                    <li><a href="faqs.php">FAQs</a></li>
                </ul>
        	</li>
		<?}?>            
		</ul>-->
	</div>
</body>
</html>