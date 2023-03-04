    <nav class="l-breadcrumb" aria-label="Breadcrumb">
        <div class="l-breadcrumb__main">
          <ol class="l-breadcrumb__list" itemscope itemtype="http://schema.org/BreadcrumbList">
            <?php
              if(function_exists('bcn_display')) {
                bcn_display();
              }
            ?>
          </ol>
        </div>
      </nav>
