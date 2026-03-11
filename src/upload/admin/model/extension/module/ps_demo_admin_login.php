<?php
class ModelExtensionModulePsDemoAdminLogin extends Model
{
    /**
     * Get Users
     *
     * Get the record of the user records in the database.
     *
     * @param array<string, mixed> $data array of filters
     *
     * @return array<int, array<string, mixed>> user records
     *
     * @example
     *
     * $filter_data = [
     *     'filter_username'      => 'Username',
     *     'filter_name'          => 'User Name',
     *     'filter_email'         => 'demo@opencart.com',
     *     'filter_user_group_id' => 1,
     *     'filter_status'        => 1,
     *     'filter_ip'            => '',
     *     'sort'                 => 'username',
     *     'order'                => 'DESC',
     *     'start'                => 0,
     *     'limit'                => 10
     * ];
     *
     * $this->load->model('user/user');
     *
     * $results = $this->model_user_user->getUsers($filter_data);
     */
    public function getUsers(array $data = []): array
    {
        $sql = "SELECT *, CONCAT(`u`.`firstname`, ' ', `u`.`lastname`) AS `name`, (SELECT `ug`.`name` FROM `" . DB_PREFIX . "user_group` `ug` WHERE `ug`.`user_group_id` = `u`.`user_group_id`) AS `user_group` FROM `" . DB_PREFIX . "user` `u`";

        $implode = [];

        if (!empty($data['filter_username'])) {
            $implode[] = "LCASE(`u`.`username`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_username']) . '%') . "'";
        }

        if (!empty($data['filter_name'])) {
            $implode[] = "LCASE(CONCAT(`u`.`firstname`, ' ', `u`.`lastname`)) LIKE '" . $this->db->escape('%' . oc_strtolower($data['filter_name']) . '%') . "'";
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "LCASE(`u`.`email`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_email']) . '%') . "'";
        }

        if (!empty($data['filter_user_group_id'])) {
            $implode[] = "`u`.`user_group_id` = '" . (int) $data['filter_user_group_id'] . "'";
        }

        if (!empty($data['filter_ip'])) {
            $implode[] = "`u`.`user_id` IN (SELECT `user_id` FROM `" . DB_PREFIX . "user_login` WHERE `ip` LIKE '" . $this->db->escape('%' . oc_strtolower($data['filter_ip']) . '%') . "')";
        }

        if (isset($data['filter_status']) && $data['filter_status'] !== '') {
            $implode[] = "`u`.`status` = '" . (int) $data['filter_status'] . "'";
        }

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        $sort_data = [
            'username',
            'name',
            'u.email',
            'user_group',
            'status',
            'ip',
            'u.date_added'
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY `username`";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int) $data['start'] . "," . (int) $data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }
}
