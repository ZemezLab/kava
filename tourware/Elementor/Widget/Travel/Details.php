<?php
namespace Tourware\Elementor\Widget\Travel;

use Tourware\Elementor\Widget\Details\AbstractDetails;

class Details extends AbstractDetails {
    /**
     * @return string
     */
    public function get_name()
    {
        return 'tourware-travel-details';
    }

    /**
     * @return string
     */
    public function get_title()
    {
        return __( 'Travel Details' );
    }

    /**
     * @return string
     */
    protected function getPostTypeName()
    {
        return 'tytotravels';
    }

    /**
     * @return string
     */
    protected function getRecordTypeName()
    {
        return 'travel';
    }

    protected function getContent($post) {
        $settings = $this->get_settings_for_display();
        $repository = \Tourware\Repository\Travel::getInstance();
        $item_data = $repository->findOneByPostId($post);

        $content = [];
        if ($settings['type'] == 'countries') {
            $t_countries = $item_data->getCountries();
            if (!empty($t_countries)) {
                foreach ($t_countries as $t_country) {
                    $content[] = $t_country->official_name_de;
                }
            }
            if (empty($countries)) {
//                $record->_destination (?)
            }
            //TODO use countries taxonomy
        } elseif ($settings['type'] == 'additional_field') {
            foreach ( $settings['additional_fields_list'] as $index => $item ) {
                if ($af = $item_data->getAdditionalField($item['field'])) $content[] = [ 'icon' => $item['field_icon'], 'text' => $af];
            }
        } elseif ($settings['type'] == 'contact_person') {
            $user = $item_data->getResponsibleUser();
            foreach ( $settings['contact_fields_list'] as $index => $item ) {
                $field = $item['field'];
                if ($field == 'name') {
                    $name = trim($user->firstname.' '.$user->lastname);
                    if ($name) $content[] = [ 'icon' => $item['field_icon'], 'text' => $name];
                } else {
                    if ($cf = $user->$field) $content[] = [ 'icon' => $item['field_icon'], 'text' => $cf];
                }
            }
        } elseif ($settings['type'] == 'tags' && !empty($settings['tags'])) {
            $tags = $item_data->getTags();
            foreach ($tags as $tag) {
                if (in_array($tag->id, $settings['tags']))
                    $content[] =  $tag->name;
            }
        } elseif ($settings['type'] == 'persons') {
            $persons_str = $item_data->getPaxMin() ? $item_data->getPaxMin() : '';
            $persons_str .= $item_data->getPaxMax() ? ' - '.$item_data->getPaxMax() : '';
            if ($persons_str) $content[] = $settings['prefix'].$persons_str.$settings['suffix'];
        } elseif ($settings['type'] == 'duration') {
            if ($duration = $item_data->getItineraryLength())
                $content[] = $settings['prefix'].$duration.$settings['suffix'];
        } elseif ($settings['type'] == 'dates') {
            $dates = $item_data->getDates();
            $date_format = 'd.m.Y';
            if (count($dates) == 1) {
                $date_start = $dates[0]->start;
                $date_end = $dates[0]->end;
            } else if (count($dates) > 1) {
                foreach ($dates as $date) {
                    if (isset($date->tags)) {
                        foreach ($date->tags as $date_tag) {
                            if (strtolower($date_tag->name) == 'default') {
                                $date_start = $date->start;
                                $date_end = $date->end;
                            }
                        }
                    }
                }
                if (empty($date_start)) $date_start = $dates[0]->start;
                if (empty($date_end)) $date_end = $dates[0]->end;
            }
            if (!empty($date_start) && !empty($date_end)) {
                $dates_str = date_i18n($date_format, strtotime($date_start)).' - '.date_i18n($date_format, strtotime($date_end));
                $content[] = $dates_str;
            }
        } elseif ($settings['type'] == 'price') {
            if ($price = $item_data->getPrice())
                $content[] = $settings['prefix'].number_format($price, 0, ',', '.').$settings['suffix'];
        }

        return $content;
    }

}