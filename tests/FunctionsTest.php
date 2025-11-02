<?php
use PHPUnit\Framework\TestCase;

class FunctionsTest extends TestCase
{
    protected function setUp(): void
    {
        // Ensure content files are present for tests; use the existing content dir
        $this->contentPath = __DIR__ . '/../content';
    }

    public function test_site_name_outputs_config_name()
    {
        ob_start();
        site_name();
        $output = ob_get_clean();

        $this->assertStringContainsString('Simple PHP Website', $output);
    }

    public function test_site_url_outputs_config_site_url()
    {
        ob_start();
        site_url();
        $output = ob_get_clean();

        // config sets site_url to empty string
        $this->assertSame('', $output);
    }

    public function test_site_version_outputs_config_version()
    {
        ob_start();
        site_version();
        $output = ob_get_clean();

        $this->assertSame('v3.1', $output);
    }

    public function test_nav_menu_outputs_links_and_active_class()
    {
        // Simulate a query string
        $_SERVER['QUERY_STRING'] = 'page=about-us';

        ob_start();
        nav_menu(' | ');
        $output = ob_get_clean();

        $this->assertStringContainsString('About Us', $output);
        $this->assertStringContainsString('class="item  active"', $output);

        // Clean up
        unset($_SERVER['QUERY_STRING']);
    }

    public function test_page_title_returns_transformed_page_name()
    {
        $_GET['page'] = 'about-us';

        ob_start();
        page_title();
        $output = ob_get_clean();

        $this->assertSame('About Us', $output);

        unset($_GET['page']);
    }

    public function test_page_content_loads_home_and_404()
    {
        // Test existing page (home)
        $_GET['page'] = 'home';
        ob_start();
        page_content();
        $homeOutput = ob_get_clean();

        $this->assertNotEmpty($homeOutput);

        // Test missing page triggers 404
        $_GET['page'] = 'does-not-exist';
        ob_start();
        page_content();
        $notFoundOutput = ob_get_clean();

        $this->assertNotEmpty($notFoundOutput);
        $this->assertStringContainsString('404', $notFoundOutput);

        unset($_GET['page']);
    }

    public function test_init_includes_template()
    {
        // The template echoes content when required; capture output from init
        ob_start();
        init();
        $output = ob_get_clean();

        // Template contains a known piece like <title> or site name
        $this->assertStringContainsString('Simple PHP Website', $output);
    }
}
