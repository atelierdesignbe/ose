<?php
  $args = array(
  'post_type' => 'publications',
  'post_status' => 'publish',
  'posts_per_page' => 2,
);

  $publications = new WP_Query($args);
?>
<!-- <div class="relative">
  <div class="">
    <div class="grid grid-base">
      <div class="col-span-12 md:col-span-8">
        <div class="flex flex-col @@:gap-y-[24px] items-start ">
          <h2 class="heading heading-2xl heading-primary text-balance">Fresh insights to inform action</h2>
          <p class="paragraph paragraph-primary paragraph-md">Explore our most recent publications, policy briefs and academic papers. Designed to be accessible and actionable, these outputs contribute to public debates and help decision-makers navigate complex social realities.</p>
          <a href="/publications" class="button button-primary button-flat">
            <span class="button-title">See all publications</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</div> -->