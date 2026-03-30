<?php
class ControllerExtensionModulePsDemoAdminLogin extends Controller
{
    /**
     * @var string The support email address.
     */
    const EXTENSION_EMAIL = 'support@playfulsparkle.com';

    /**
     * @var string The URL to the support website.
     */
    const SUPPORT_URL = 'https://support.playfulsparkle.com';

    /**
     * @var string The GitHub repository URL of the extension.
     */
    const GITHUB_REPO_URL = 'https://github.com/playfulsparkle/oc3_demo_admin_login';

    private $error = array();

    public function index()
    {
        $this->load->language('extension/module/ps_demo_admin_login');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('module_ps_demo_admin_login', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['username'])) {
            $data['error_username'] = $this->error['username'];
        } else {
            $data['error_username'] = '';
        }

        if (isset($this->error['password'])) {
            $data['error_password'] = $this->error['password'];
        } else {
            $data['error_password'] = '';
        }

        if (isset($this->error['banner_description'])) {
            $data['error_banner_description'] = (array) $this->error['banner_description'];
        } else {
            $data['error_banner_description'] = array();
        }

        if (isset($this->error['banner_text_color'])) {
            $data['error_banner_text_color'] = $this->error['banner_text_color'];
        } else {
            $data['error_banner_text_color'] = '';
        }

        if (isset($this->error['banner_background_color'])) {
            $data['error_banner_background_color'] = $this->error['banner_background_color'];
        } else {
            $data['error_banner_background_color'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/ps_demo_admin_login', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['action'] = $this->url->link('extension/module/ps_demo_admin_login', 'user_token=' . $this->session->data['user_token'], true);

        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

        $data['user_token'] = $this->session->data['user_token'];

        if (isset($this->request->post['module_ps_demo_admin_login_status'])) {
            $data['module_ps_demo_admin_login_status'] = (bool) $this->request->post['module_ps_demo_admin_login_status'];
        } else {
            $data['module_ps_demo_admin_login_status'] = (bool) $this->config->get('module_ps_demo_admin_login_status');
        }

        if (isset($this->request->post['module_ps_demo_admin_login_username'])) {
            $data['module_ps_demo_admin_login_username'] = $this->request->post['module_ps_demo_admin_login_username'];
        } else {
            $data['module_ps_demo_admin_login_username'] = $this->config->get('module_ps_demo_admin_login_username');
        }

        if (isset($this->request->post['module_ps_demo_admin_login_password'])) {
            $data['module_ps_demo_admin_login_password'] = $this->request->post['module_ps_demo_admin_login_password'];
        } else {
            $data['module_ps_demo_admin_login_password'] = $this->config->get('module_ps_demo_admin_login_password');
        }

        if (isset($this->request->post['module_ps_demo_admin_login_banner_status'])) {
            $data['module_ps_demo_admin_login_banner_status'] = (bool) $this->request->post['module_ps_demo_admin_login_banner_status'];
        } else {
            $data['module_ps_demo_admin_login_banner_status'] = (bool) $this->config->get('module_ps_demo_admin_login_banner_status');
        }

        if (isset($this->request->post['module_ps_demo_admin_login_banner_description'])) {
            $data['module_ps_demo_admin_login_banner_description'] = (array) $this->request->post['module_ps_demo_admin_login_banner_description'];
        } else {
            $data['module_ps_demo_admin_login_banner_description'] = (array) $this->config->get('module_ps_demo_admin_login_banner_description');
        }

        if (isset($this->request->post['module_ps_demo_admin_login_banner_text_color'])) {
            $data['module_ps_demo_admin_login_banner_text_color'] = $this->request->post['module_ps_demo_admin_login_banner_text_color'];
        } else {
            $data['module_ps_demo_admin_login_banner_text_color'] = $this->config->get('module_ps_demo_admin_login_banner_text_color');
        }

        if (isset($this->request->post['module_ps_demo_admin_login_banner_background_color'])) {
            $data['module_ps_demo_admin_login_banner_background_color'] = $this->request->post['module_ps_demo_admin_login_banner_background_color'];
        } else {
            $data['module_ps_demo_admin_login_banner_background_color'] = $this->config->get('module_ps_demo_admin_login_banner_background_color');
        }

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        $data['text_contact'] = sprintf($this->language->get('text_contact'), self::SUPPORT_URL, self::GITHUB_REPO_URL, self::EXTENSION_EMAIL);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/ps_demo_admin_login', $data));
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'extension/module/ps_demo_admin_login')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            if (
                utf8_strlen(trim($this->request->post['module_ps_demo_admin_login_username'])) <= 1 ||
                utf8_strlen(trim($this->request->post['module_ps_demo_admin_login_username'])) >= 255
            ) {
                $this->error['username'] = $this->language->get('error_username');
            }

            if (
                utf8_strlen(trim($this->request->post['module_ps_demo_admin_login_password'])) <= 1 ||
                utf8_strlen(trim($this->request->post['module_ps_demo_admin_login_password'])) >= 255
            ) {
                $this->error['password'] = $this->language->get('error_password');
            }

            if (isset($this->request->post['module_ps_demo_admin_login_banner_description'])) {
                foreach ($this->request->post['module_ps_demo_admin_login_banner_description'] as $language_id => $value) {
                    if (utf8_strlen(trim(($value))) === 0) {
                        $this->error['banner_description'][$language_id] = $this->language->get('error_description');
                    }
                }
            }
        }

        return !$this->error;
    }


    public function install()
    {
        $this->load->model('setting/setting');

        $data = array(
            'module_ps_demo_admin_login_status' => 0,
            'module_ps_demo_admin_login_username' => '',
            'module_ps_demo_admin_login_password' => '',
            'module_ps_demo_admin_login_banner_status' => 0,
            'module_ps_demo_admin_login_banner_description' => array(),
            'module_ps_demo_admin_login_banner_text_color' => '#000000',
            'module_ps_demo_admin_login_banner_background_color' => '#ffa800',
        );

        $this->model_setting_setting->editSetting('module_ps_demo_admin_login', $data);
    }

    public function uninstall()
    {

    }

    /**
     * Autocomplete
     *
     * @return void
     */
    public function user_autocomplete(): void
    {
        $json = array();

        if (isset($this->request->get['filter_username']) || isset($this->request->get['filter_name']) || isset($this->request->get['filter_email'])) {
            if (isset($this->request->get['filter_username'])) {
                $filter_username = $this->request->get['filter_username'];
            } else {
                $filter_username = '';
            }

            if (isset($this->request->get['filter_name'])) {
                $filter_name = $this->request->get['filter_name'];
            } else {
                $filter_name = '';
            }

            if (isset($this->request->get['filter_email'])) {
                $filter_email = $this->request->get['filter_email'];
            } else {
                $filter_email = '';
            }

            $filter_data = array(
                'filter_username' => $filter_username,
                'filter_name' => $filter_name,
                'filter_email' => $filter_email,
                'start' => 0,
                'limit' => $this->config->get('config_autocomplete_limit')
            );


            $this->load->model('extension/module/ps_demo_admin_login');

            $results = $this->model_extension_module_ps_demo_admin_login->getUsers($filter_data);

            foreach ($results as $result) {
                $json[] = array('name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))) + $result;
            }
        }

        $sort_order = array();

        foreach ($json as $key => $value) {
            $sort_order[$key] = $value['username'];
        }

        array_multisort($sort_order, SORT_ASC, $json);

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
