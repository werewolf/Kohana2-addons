<?php

/**
 * @author Werewolf
 */
class form extends form_Core
{

    /**
     * Used for encoding form values
     * @staticvar array $prepped_fields
     * @param array|string $str
     * @param string $field_name
     * @return array|string
     */
    public static function prep($str = '', $field_name = '')
    {
        static $prepped_fields = array();

        if (is_array($str))
        {
            foreach ($str as $key => $val)
            {
                $str[$key] = self::prep($val);
            }

            return $str;
        }

        if ($str === '')
        {
            return '';
        }

        // we've already prepped a field with this name
        // @todo need to figure out a way to namespace this so
        // that we know the *exact* field and not just one with
        // the same name
        if (isset($prepped_fields[$field_name]))
        {
            return $str;
        }

        $str = html::specialchars($str);

        if ($field_name != '')
        {
            $prepped_fields[$field_name] = $str;
        }
        return $str;
    }
    /**
     *
     * @param string $field
     * @param string $default
     * @return string
     */
    public function set_value($field, $default = '')
    {
        // Getting validation object
        $validation = Kohana::instance()->get_validation();
        if (! ($validation instanceof Validation))
        {
            if (!isset($_POST[$field]))
            {
                return $default;
            }
            return form::prep($_POST[$field], $field);
        }
        $post = $validation->as_array();

        // Will fill only if form has been submitted
        if ($validation->submitted() && isset($post[$field]))
        {
            return form::prep($post[$field], $field);
        } else
        {
            return $default;
        }
    }
    /**
     *
     * @param string $field
     * @param string $value
     * @param boolean $default
     * @return string
     */
    public function set_radio($field = '', $value = '', $default = FALSE)
    {
        // Getting validation object
        $validation = Kohana::instance()->get_validation();
        if (! ($validation instanceof Validation))
        {
            if (!isset($_POST[$field]))
            {
                if (count($_POST) === 0 AND $default == TRUE)
                {
                    return ' checked="checked"';
                }
                return '';
            }
            $field = $_POST[$field];

            if (is_array($field))
            {
                if (!in_array($value, $field))
                {
                    return '';
                }
            }
            else
            {
                if (($field == '' OR $value == '') OR ($field != $value))
                {
                    return '';
                }
            }

            return ' checked="checked"';
        }
        // Validation object exists
        $post = $validation->as_array();

        $submitted = $validation->submitted();
        if (!$submitted || !isset($post[$field]))
        {
            if ($default === TRUE AND !$submitted)
            {
                return ' checked="checked"';
            }
            return '';
        }

        $field = $post[$field];
        if (is_array($field))
        {
            if (!in_array($value, $field))
            {
                return '';
            }
        }
        else
        {
            if (($field == '' OR $value == '') OR ($field != $value))
            {
                return '';
            }
        }

        return ' checked="checked"';
    }
    public function set_checkbox($field = '', $value = '', $default = FALSE)
    {
        return self::set_radio($field,$value,$default);
    }
}