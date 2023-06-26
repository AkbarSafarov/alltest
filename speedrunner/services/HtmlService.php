<?php

namespace speedrunner\services;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\VarDumper;


class HtmlService
{
    public static function saveButtons(array $buttons_list, $form_action = null)
    {
        $form_action = $form_action ?? Url::to();
        
        foreach ($buttons_list as $key => $button) {
            switch ($button) {
                case 'save_create':
                    parse_str(parse_url($form_action, PHP_URL_QUERY), $form_action_query);
                    $form_action_query['save_and_create'] = true;
                    $form_action_query = http_build_query($form_action_query);
                    
                    $result[] = Html::submitButton(
                        Html::tag('i', null, ['class' => 'fas fa-save me-2']) . Yii::t('app', 'Сохранить и создать'),
                        [
                            'class' => 'btn btn-blue ms-1',
                            'formaction' => parse_url($form_action, PHP_URL_PATH) . ($form_action_query ? "?$form_action_query" : null),
                        ]
                    );
                    
                    break;
                case 'save_update':
                    parse_str(parse_url($form_action, PHP_URL_QUERY), $form_action_query);
                    $form_action_query['save_and_update'] = true;
                    $form_action_query = http_build_query($form_action_query);
                    
                    $result[] = Html::submitButton(
                        Html::tag('i', null, ['class' => 'fas fa-save me-2']) . Yii::t('app', 'Сохранить и редактировать'),
                        [
                            'class' => 'btn btn-info ms-1',
                            'formaction' => parse_url($form_action, PHP_URL_PATH) . ($form_action_query ? "?$form_action_query" : null),
                        ]
                    );
                    
                    break;
                case 'save':
                    $result[] = Html::submitButton(
                        Html::tag('i', null, ['class' => 'fas fa-save me-2']) . Yii::t('app', 'Сохранить'),
                        ['class' => 'btn btn-success ms-1']
                    );
                    
                    break;
                default:
                    $result[] = $button;
            }
        }
        
        return implode(null, $result ?? []);
    }
    
    public static function dump($var, $depth = 10, $highlight = true)
    {
        return VarDumper::dump($var, $depth, $highlight);
    }
    
    public static function purify($value, $allowed_chars = [])
    {
        $allowed_chars = $allowed_chars ?: [
            '%7B' => '{',
            '%7D' => '}',
            '%3A' => ':',
            '&amp;' => '&',
        ];
        
        $config = \HTMLPurifier_HTML5Config::createDefault();
        $config->set('Cache.DefinitionImpl', null);
        $config->set('HTML.SafeIframe', true);
        $config->set('HTML.MaxImgLength', null);
        $config->set('Attr.AllowedFrameTargets', ['_blank', '_self', '_parent', '_top']);
        $config->set('CSS.MaxImgLength', null);
        $config->set('HTML.XHTML', true);
        
        $html_definition = $config->getDefinition('HTML', true, true);
        
        $html_definition->addElement('meta', 'Inline', 'Empty', 'Common', [
            'name' => 'Text',
            'property' => 'Text',
            'content' => 'Text',
        ]);
        
        $html_definition->addElement('iframe', 'Block', 'Inline', 'Common', [
            'src' => 'Text',
        ]);
        
        $purifier = new \HTMLPurifier($config);
        $value = $purifier->purify($value);
        
        foreach ($allowed_chars as $from => $to) {
            $value = str_replace($from, $to, $value);
        }
        
        return $value;
    }
}
