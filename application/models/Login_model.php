<?php

/**
 * Class Login_model
 * Model for Login related actions to the database
 */
class Login_model extends CI_Model
{
    /**
     * Get a recovery hash for a certain email
     *
     * @param $email
     *
     * @return bool
     */
    public function get_hash($email)
    {
        $this->db->cache_off();
        $this->db->select('wachtwoord, rank, id, engels');
        $this->db->from('gebruikers');
        $this->db->where('email', $email);
        $query = $this->db->get();
        $this->db->cache_on();
        if ($query->num_rows() > 0) {
            return $query->row();
        }

        return false;
    }

    /**
     * Set a password hash for a certain email
     *
     * @param $email
     * @param $hash
     */
    public function set_hash($email, $hash)
    {
        $this->db->set('wachtwoord', $hash);
        $this->db->where('email', $email);
        $this->db->update('gebruikers');
    }

    /**
     * Remove the hash belonging to the user_id
     *
     * @param $id
     */
    public function unset_recovery($id)
    {
        $data = [
            'recovery' => null,
            'recovery_valid' => null,
        ];
        $this->db->where('id', $id);
        $this->db->update('gebruikers', $data);
    }

    /**
     * Use a recovery hash to get the id and email of an user
     *
     * @param $recovery hash
     *
     * @return bool
     */
    public function get_id_and_mail($recovery)
    {
        $this->db->cache_off();
        $this->db->select('id, email');
        $query = $this->db->get_where(
            'gebruikers',
            [
                'recovery' => $recovery,
                'recovery_valid >=' => date('Y-m-d H:i:s'),
            ]
        );
        $this->db->cache_on();
        if ($query->num_rows() > 0) {
            return $query->result()[0];
        }

        return false;
    }

    /**
     * Set a recovery hash for a certain mailadress
     *
     * @param      $email    string mailadress
     * @param bool $new_user For new users we allocate a longer validity period
     *
     * @return array|bool
     */
    public function set_recovery($email, $new_user = false)
    {
        $data = [
            'recovery' => $this->getToken(32),
            'recovery_valid' => ($new_user ? date('Y-m-d H:i:s', strtotime('now + 1 week')) : date(
                'Y-m-d H:i:s',
                strtotime(
                    'now + 1 hour'
                )
            )),
        ];
        $this->db->select('id');
        $query = $this->db->get_where('gebruikers', ['email' => $email]);
        if ($query->num_rows() === 0) {
            return false;
        }
        $id = $query->result()[0]->id;
        if ($this->update($id, $data) > 0) {
            $data['email'] = $email;

            return $data;
        }

        return false;
    }

    /**
     * Function to generate a random hash that can be used for recovery
     *
     * @param int $length
     *
     * @return string
     */
    private function getToken($length = 32)
    {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= "0123456789";
        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[$this->crypto_rand_secure(0, strlen($codeAlphabet))];
        }

        return $token;
    }

    /**
     * Function to get a random number
     *
     * @param $min
     * @param $max
     *
     * @return int
     */
    private function crypto_rand_secure($min, $max)
    {
        $range = $max - $min;
        if ($range < 0) {
            return $min;
        } // not so random...
        $log = log($range, 2);
        $bytes = (int)($log / 8) + 1; // length in bytes
        $bits = (int)$log + 1; // length in bits
        $filter = (int)(1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);

        return $min + $rnd;
    }

    /**
     * Update a certain user_id
     *
     * @param $id
     * @param $data
     *
     * @return mixed
     */
    private function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('gebruikers', $data);
        $this->db->cache_delete('profile');

        return $this->db->affected_rows();
    }
}
