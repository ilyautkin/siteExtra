<?php

/** @var $modx modX */
if (!$modx = $object->xpdo AND !$object->xpdo instanceof modX) {
    return true;
}

/** @var $options */
switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:

        $site_start = $modx->getObject('modResource', $modx->getOption('site_start'));
        if ($site_start) {
            $site_start->set('hidemenu', true);
            $site_start->save();
        }
        $chunks = array(
                'content'
            );
        foreach ($chunks as $chunk_name) {
            if ($chunk = $modx->getObject('modChunk', array('name' => $chunk_name))) {
                $chunk->set('snippet', str_replace('SITE_START_ID', $site_start->id, $chunk->snippet));
                $chunk->save();
            }
        }
            
        /* robots.txt */
        $alias = 'robots';
        $parent = 0;
        $templateId = 0;
        if (!$resource = $modx->getObject('modResource', array('alias' => $alias))) {
            $resource = $modx->newObject('modResource');
        }
        $resource->fromArray(array(
            'class_key'    => 'modDocument',
            'menuindex'    => 1010,
            'pagetitle'    => $alias . '.txt',
            'alias'        => $alias,
            'uri'          => $alias . '.txt',
            'uri_override' => 0,
            'published'    => 1,
            'publishedon'  => time(),
            'hidemenu'     => 1,
            'richtext'     => 0,
            'parent'       => $parent,
            'template'     => $templateId,

            'searchable'   => 0,
            'content_type' => 3,
            'contentType'  => 'text/plain',

            'content' => preg_replace(array('/^\n/', '/[ ]{2,}|[\t]/'), '', "
                User-agent: *
                Allow: /
                
                Host: {\$_modx->config.http_host}
                
                Sitemap: {\$_modx->config.site_url}sitemap.xml
            ")
        ));
        $resource->save();


        
        if (isset($options['site_template_name']) && !empty($options['site_template_name'])) {
            $template = $modx->getObject('modTemplate', array('templatename' => $options['site_template_name']));
        }
        if ($template) {
            $templateId = $template->get('id');
        } else {
            $templateId = $modx->getOption('default_template');
        }

        /* О компании */
        $alias = 'about';
        $parent = 0;
        if ($modx->getOption('cultureKey') == 'ru') {
            $pagetitle = 'Информация о нас';
            $menutitle = 'О компании';
            $content = '
                <p>Медиабизнес слабо допускает конструктивный формирование имиджа, учитывая результат предыдущих медиа-кампаний. Конвесия покупателя, конечно, по-прежнему востребована. Рыночная информация стабилизирует пресс-клиппинг, полагаясь на инсайдерскую информацию. План размещения без оглядки на авторитеты не так уж очевиден.</p>
                <p>Психологическая среда индуцирует конструктивный стратегический маркетинг, оптимизируя бюджеты. Медиапланирование поддерживает общественный ребрендинг. Медиамикс правомочен. Медиапланирование стабилизирует стратегический рекламоноситель.</p>
                <p>Рекламная площадка усиливает медиабизнес. Эволюция мерчандайзинга притягивает департамент маркетинга и продаж, оптимизируя бюджеты. Поэтому таргетирование стремительно усиливает целевой трафик. Потребление, вопреки мнению П.Друкера, редко соответствует рыночным ожиданиям. Имидж, следовательно, программирует медиамикс.</p>
            ';
        } else {
            $pagetitle = 'About company';
            $menutitle = 'About';
            $content = '
                <p>Day blessed moved likeness. Sea whales together blessed together. Above beast have herb, moveth waters every place light gathering God beginning rule have seas very beast lesser moved yielding, god lights man dry. Every, can\'t you\'ll fill gathered whose midst moved.</p>
                <p>Gathered to it made sixth. Made man. Cattle morning blessed living. Under signs, also forth to lesser was seasons appear she\'d from you saying thing said likeness image he.</p>
                <p>Together sixth whose fruitful isn\'t them god creeping you. Seas bearing isn\'t moved them, very place to creature. First, upon. Evening itself, beginning first lesser lights tree Fill He gathering.</p>
            ';
        }
        if (!$resource = $modx->getObject('modResource', array('alias' => $alias))) {
            $resource = $modx->newObject('modResource');
            $resource->set('content', preg_replace(array('/^\n/', '/[ ]{2,}|[\t]/'), '', $content));
        }
        $resource->fromArray(array(
            'class_key'    => 'modDocument',
            'menuindex'    => 1,
            'pagetitle'    => $pagetitle,
            'menutitle'    => $menutitle,
            'isfolder'     => 1,
            'alias'        => $alias,
            'uri'          => $alias,
            'uri_override' => 0,
            'published'    => 1,
            'publishedon'  => time(),
            'hidemenu'     => 0,
            'richtext'     => 1,
            'parent'       => $parent,
            'template'     => $templateId
        ));
        $resource->save();

        /* Специалисты */
        $alias = 'specialists';
        $parent = 0;
        $addspecs = false;
        if ($modx->getOption('cultureKey') == 'ru') {
            $pagetitle = 'Наши сотрудники';
            $menutitle = 'Специалисты';
        } else {
            $pagetitle = 'Staff of our company';
            $menutitle = 'Our team';
        }
        if (!$resource = $modx->getObject('modResource', array('alias' => $alias))) {
            $resource = $modx->newObject('modResource');
            $addspecs = true;
        }
        $resource->fromArray(array(
            'class_key'    => 'modDocument',
            'menuindex'    => 2,
            'pagetitle'    => $pagetitle, // 'Наши сотрудники',
            'menutitle'    => $menutitle, // 'Специалисты',
            'isfolder'     => 1,
            'alias'        => $alias,
            'uri'          => $alias,
            'uri_override' => 0,
            'published'    => 1,
            'publishedon'  => time(),
            'hidemenu'     => 0,
            'richtext'     => 0,
            'parent'       => $parent,
            'template'     => $templateId,
            'content'      => preg_replace(array('/^\n/', '/[ ]{2,}|[\t]/'), '', "
                <p></p>
            ")
        ));
        $resource->save();
        $specAlias = $alias;
        
        $chunks = array(
                'aside',
                'content'
            );
        foreach ($chunks as $chunk_name) {
            if ($chunk = $modx->getObject('modChunk', array('name' => $chunk_name))) {
                $snippet = str_replace('SITE_SPECS_ID', $resource->id, $chunk->snippet);
                if ($modx->getOption('cultureKey') != 'ru') {
                    $snippet = str_replace('Наши специалисты', 'Our team', $snippet);
                }
                $chunk->set('snippet', $snippet);
                $chunk->save();
            }
        }
        
        if ($addspecs) {
            $resource->setTVValue('show_on_page', 'content||gallery');
            $specParent = $resource->get('id');
            if ($modx->getOption('cultureKey') == 'ru') {
                $positions = array(
                    'Маркетолог',
                    'Маркетолог',
                    'PR-менеджер',
                    'Директор',
                    'Оператор колл-центра'
                );
            } else {
                $positions = array(
                    'Marketer',
                    'Marketer',
                    'PR-manager',
                    'CEO',
                    'Call center operator'
                );
            }
            if ($modx->getOption('cultureKey') == 'ru') {
                $pagetitle = 'Сотрудник ';
            } else {
                $pagetitle = 'Specialist #';
            }
            for ($i = 1; $i <= 5; $i++) {
                /* Специалист 1 */
                $alias = 'spec-' . $i;
                if (!$resource = $modx->getObject('modResource', array('alias' => $alias))) {
                    $resource = $modx->newObject('modResource');
                }
                $resource->fromArray(array(
                    'class_key'    => 'modDocument',
                    'menuindex'    => $i,
                    'pagetitle'    => $pagetitle . $i, // 'Сотрудник ' . $i,
                    'isfolder'     => 0,
                    'alias'        => $alias,
                    'uri'          => $specAlias . '/' . $alias,
                    'uri_override' => 0,
                    'published'    => 1,
                    'publishedon'  => time() - 60 * 60 * $i,
                    'hidemenu'     => 1,
                    'richtext'     => 1,
                    'parent'       => $specParent,
                    'template'     => $templateId,
                    'content'      => preg_replace(array('/^\n/', '/[ ]{2,}|[\t]/'), '', "
                        <p></p>
                    ")
                ));
                $resource->save();
                $resource->setTVValue('img', $modx->getOption('assets_url') . 'components/' . strtolower($options['site_category']) . '/web/img/spec' . $i . '.png');
                $resource->setTVValue('subtitle', $positions[$i-1]);
            }
        }

        /* Отзывы */
        $alias = 'reviews';
        $parent = 0;
        $addReviews = false;
        if ($modx->getOption('cultureKey') == 'ru') {
            $pagetitle = 'Отзывы наших клиентов';
            $menutitle = 'Отзывы';
        } else {
            $pagetitle = 'Feedback from our customers';
            $menutitle = 'Testimonials';
        }
        if (!$resource = $modx->getObject('modResource', array('alias' => $alias))) {
            $resource = $modx->newObject('modResource');
            $addReviews = true;
        }
        if (in_array('Collections', $options['install_addons'])) {
            $collection_type = 'CollectionContainer';
        } else {
            $collection_type = 'modDocument';
        }
        $resource->fromArray(array(
            'class_key'    => $collection_type,
            'menuindex'    => 3,
            'pagetitle'    => $pagetitle, // 'Отзывы наших клиентов',
            'menutitle'    => $menutitle, // 'Отзывы',
            'isfolder'     => 1,
            'alias'        => $alias,
            'uri'          => $alias,
            'uri_override' => 0,
            'published'    => 1,
            'publishedon'  => time(),
            'hidemenu'     => 0,
            'richtext'     => 1,
            'parent'       => $parent,
            'template'     => $templateId,
            'content'      => preg_replace(array('/^\n/', '/[ ]{2,}|[\t]/'), '', "
                <p></p>
            ")
        ));
        $resource->save();
        $reviewsAlias = $alias;
        
        if ($addReviews) {
            $reviewParent = $resource->get('id');
            if ($modx->getOption('cultureKey') == 'ru') {
                $pagetitle = 'Отзыв ';
                $reviews = array(
                    "<p>Восприятие, на первый взгляд, отражает гендерный стимул. Чем больше люди узнают друг друга, тем больше воспитание иллюстрирует коллективный импульс. Придерживаясь жестких принципов социального Дарвинизма, предсознательное отражает страх, также это подчеркивается в труде Дж.Морено \"Театр Спонтанности\". Ригидность отчуждает групповой эгоцентризм.</p>
                    <p>Рефлексия, как справедливо считает Ф.Энгельс, представляет собой экзистенциальный тест. Идентификация, по определению, отчуждает инсайт. Акцентуированная личность выбирает эмпирический страх. Страх, согласно традиционным представлениям, теоретически возможен.</p>
                    <p>Бессознательное, в представлении Морено, однородно выбирает кризис, это обозначено Ли Россом как фундаментальная ошибка атрибуции, которая прослеживается во многих экспериментах. Действие осознаёт гештальт. Как отмечает Д.Майерс, у нас есть некоторое чувство конфликта, которое возникает с ситуации несоответствия желаемого и действительного, поэтому бессознательное просветляет инсайт. Самоактуализация осознаёт филосовский объект. Гендер, по определению, изящно отталкивает латентный интеллект, в частности, \"психозы\", индуцируемые при различных психопатологических типологиях.</p>",
                    "<p>Самонаблюдение аннигилирует индивидуальный интеллект, следовательно основной закон психофизики: ощущение изменяется пропорционально логарифму раздражителя . Код начинает потребительский импульс, что вызвало развитие функционализма и сравнительно-психологических исследований поведения. Как отмечает Жан Пиаже, субъект традиционен.</p>
                    <p>Сновидение существенно отражает стимул. Роль, иcходя из того, что мгновенно отчуждает индивидуальный аутизм. Установка отталкивает групповой эгоцентризм, таким образом, стратегия поведения, выгодная отдельному человеку, ведет к коллективному проигрышу. Чувство, в представлении Морено, косвенно.</p>
                    <p>Психосоматика выбирает конвергентный филогенез, как и предсказывают практические аспекты использования принципов гештальпсихологии в области восприятия, обучения, развития психики, социальных взаимоотношений. В заключении добавлю, контраст концептуально понимает контраст. Компульсивность, например, притягивает психоз. Самость психологически аннигилирует автоматизм. Психосоматика фундаментально притягивает когнитивный объект. Аномия представляет собой концептуальный гомеостаз.</p>",
                    "<p>Акцентуированная личность интегрирует психоанализ. Чувство, как справедливо считает Ф.Энгельс, важно отталкивает девиантный филогенез. Всякая психическая функция в культурном развитии ребенка появляется на сцену дважды, в двух планах,— сперва социальном, потом — психологическом, следовательно рефлексия вероятна. Установка вызывает позитивистский психоз.</p>
                    <p>Сознание, конечно, начинает институциональный интеракционизм. Онтогенез речи, например, представляет собой конфликтный код. Когнитивная составляющая, в первом приближении, просветляет эгоцентризм. Наши исследования позволяют сделать вывод о том, что гештальт отталкивает ассоцианизм. Психе, в первом приближении, семантически представляет собой коллективный субъект. Л.С.Выготский понимал тот факт, что эскапизм начинает материалистический контраст.</p>
                    <p>Предсознательное иллюстрирует автоматизм, также это подчеркивается в труде Дж.Морено \"Театр Спонтанности\". Стратификация гомогенно отражает архетип, следовательно тенденция к конформизму связана с менее низким интеллектом. Социализация отражает культурный гомеостаз, что вызвало развитие функционализма и сравнительно-психологических исследований поведения. В связи с этим нужно подчеркнуть, что сновидение выбирает социометрический эриксоновский гипноз. Но так как книга Фридмана адресована руководителям и работникам образования, то есть сознание вызывает конформизм. Инсайт интегрирует экспериментальный интеллект.</p>"
                );
            } else {
                $pagetitle = 'Review #';
                $reviews = array(
                    "<p>Appear be green lesser signs lesser, fowl. There creature winged brought second appear it signs saying light over signs you'll their man deep, unto every make.</p>
                    <p>Thing seasons night one replenish behold fowl can't from image they're the seasons may. Lights creature whales creeping saw creeping from. Creeping so. Give male isn't place life second set rule first male Blessed very had moved seas called without Them meat creepeth.</p>
                    <p>Sea replenish forth give yielding so day. They're first be living shall great night lights male moved fourth us Living years Stars let fly evening replenish all day shall second seas.</p>",
                    "<p>Great without. Wherein heaven moved one female. And, can't wherein. Seasons fruit evening, deep deep without fruit seasons above seas i kind so herb moving a. Kind lights doesn't fill. Sixth, female very whose dry winged man replenish there.</p>
                    <p>All appear first that said together creature cattle living were. Unto green, give female given itself was said itself two be made.</p>
                    <p>Fill. Man said. Given fowl earth open abundantly have, place great signs all upon cattle bring shall. Over meat, whales earth behold creature female given called behold, seasons grass after give. All forth kind dry wherein the fill divide.</p>",
                    "<p>Earth given. After moving. I a creature firmament dominion fowl won't cattle. Made evening beginning male for in all open for from night. Make fly from, own. Fill gathered two Day, wherein fruit be behold.</p>
                    <p>Seas under together rule own green whales to also heaven there man given signs creature, for you'll was yielding i unto winged creature.</p>
                    <p>Us can't open our divided behold second divide. Gathered for was fly said own first moved earth which Female to all behold and every very don't fowl creepeth sea abundantly, him creeping fly be that divide lights tree Face so wherein thing.</p>"
                );
            }
            for ($i = 1; $i <= 3; $i++) {
                /* Отзыв 1 */
                $alias = 'review-' . $i;
                if (!$resource = $modx->getObject('modResource', array('alias' => $alias))) {
                    $resource = $modx->newObject('modResource');
                }
                $review = $reviews[$i-1] ? $reviews[$i-1] : $review[0];
                $resource->fromArray(array(
                    'class_key'    => 'modDocument',
                    'show_in_tree' => 0,
                    'menuindex'    => $i,
                    'pagetitle'    => $pagetitle .$i, // 'Отзыв ' . $i,
                    'isfolder'     => 0,
                    'alias'        => $alias,
                    'uri'          => $reviewsAlias . '/' . $alias,
                    'uri_override' => 0,
                    'published'    => 1,
                    'publishedon'  => time() - 60 * 60 * $i,
                    'hidemenu'     => 0,
                    'richtext'     => 1,
                    'parent'       => $reviewParent,
                    'template'     => $templateId,
                    'content'      => preg_replace(array('/^\n/', '/[ ]{2,}|[\t]/'), '', $review)
                ));
                $resource->save();
            }
        }

        /* Галерея */
        $alias = 'gallery';
        $parent = 0;
        $addPhotos = false;
        if ($modx->getOption('cultureKey') == 'ru') {
            $pagetitle = 'Галерея';
        } else {
            $pagetitle = 'Gallery';
        }
        if (!$resource = $modx->getObject('modResource', array('alias' => $alias))) {
            $resource = $modx->newObject('modResource');
            $addPhotos = true;
        }
        $resource->fromArray(array(
            'class_key'    => 'modDocument',
            'menuindex'    => 4,
            'pagetitle'    => $pagetitle, // 'Галерея',
            'isfolder'     => 1,
            'alias'        => $alias,
            'uri'          => $alias,
            'uri_override' => 0,
            'published'    => 1,
            'publishedon'  => time(),
            'hidemenu'     => 0,
            'richtext'     => 1,
            'parent'       => $parent,
            'template'     => $templateId,
            'content'      => preg_replace(array('/^\n/', '/[ ]{2,}|[\t]/'), '', "
                <p></p>
            ")
        ));
        $resource->save();
        
        $chunks = array(
                'block.gallery'
            );
        foreach ($chunks as $chunk_name) {
            if ($chunk = $modx->getObject('modChunk', array('name' => $chunk_name))) {
                $chunk->set('snippet', str_replace('SITE_GALLERY_ID', $resource->id, $chunk->snippet));
                $chunk->save();
            }
        }
        
        if ($modx->getOption('cultureKey') == 'ru') {
            $photo = 'Фото';
        } else {
            $photo = 'Photo';
        }
        if ($addPhotos && in_array('MIGX', $options['install_addons'])) {
            $resource->setTVValue('elements', $modx->toJSON(
                    array(
                        array('MIGX_id' => 1, 'img' => $modx->getOption('assets_url') . 'components/' . strtolower($options['site_category']) . '/web/img/gal1.jpg', 'title' => $photo . ' 1'),
                        array('MIGX_id' => 2, 'img' => $modx->getOption('assets_url') . 'components/' . strtolower($options['site_category']) . '/web/img/gal2.jpg', 'title' => $photo . ' 2'),
                        array('MIGX_id' => 3, 'img' => $modx->getOption('assets_url') . 'components/' . strtolower($options['site_category']) . '/web/img/gal3.jpg', 'title' => $photo . ' 3'),
                        array('MIGX_id' => 4, 'img' => $modx->getOption('assets_url') . 'components/' . strtolower($options['site_category']) . '/web/img/gal4.jpg', 'title' => $photo . ' 4'),
                        array('MIGX_id' => 5, 'img' => $modx->getOption('assets_url') . 'components/' . strtolower($options['site_category']) . '/web/img/gal5.jpg', 'title' => $photo . ' 5'),
                        array('MIGX_id' => 6, 'img' => $modx->getOption('assets_url') . 'components/' . strtolower($options['site_category']) . '/web/img/gal6.jpg', 'title' => $photo . ' 6'),
                    )
                ));
        }

        /* Новости */
        $alias = 'news';
        $parent = 0;
        $addNews = false;
        if ($modx->getOption('cultureKey') == 'ru') {
            $pagetitle = 'Новости компании';
            $menutitle = 'Новости';
        } else {
            $pagetitle = 'Our news';
            $menutitle = 'News';
        }
        if (!$resource = $modx->getObject('modResource', array('alias' => $alias))) {
            $resource = $modx->newObject('modResource');
            $addNews = true;
        }
        if (in_array('Collections', $options['install_addons'])) {
            $collection_type = 'CollectionContainer';
        } else {
            $collection_type = 'modDocument';
        }
        $resource->fromArray(array(
            'class_key'    => $collection_type,
            'menuindex'    => 5,
            'pagetitle'    => $pagetitle, // 'Новости компании',
            'menutitle'    => $menutitle, // 'Новости',
            'isfolder'     => 1,
            'alias'        => $alias,
            'uri'          => $alias,
            'uri_override' => 0,
            'published'    => 1,
            'publishedon'  => time(),
            'hidemenu'     => 0,
            'richtext'     => 1,
            'parent'       => $parent,
            'template'     => $templateId,
            'content'      => preg_replace(array('/^\n/', '/[ ]{2,}|[\t]/'), '', "
                <p></p>
            ")
        ));
        $resource->save();
        $newsAlias = $alias;
        
        $chunks = array(
                'aside'
            );
        foreach ($chunks as $chunk_name) {
            if ($chunk = $modx->getObject('modChunk', array('name' => $chunk_name))) {
                $chunk->set('snippet', str_replace('SITE_NEWS_ID', $resource->id, $chunk->snippet));
                $chunk->save();
            }
        }
        
        if ($addNews) {
            $newsParent = $resource->get('id');
            if ($modx->getOption('cultureKey') == 'ru') {
                $news = array(
                    "<p>Кризис жанра дает фузз, потому что современная музыка не запоминается. Очевидно, что нота заканчивает самодостаточный контрапункт контрастных фактур. Показательный пример – хамбакер неустойчив. Аллюзийно-полистилистическая композиция иллюстрирует дискретный шоу-бизнес. Как было показано выше, хамбакер продолжает звукоряд, таким образом объектом имитации является число длительностей в каждой из относительно автономных ритмогрупп ведущего голоса.</p>",
                    "<p>В заключении добавлю, open-air дает конструктивный флажолет. Гипнотический рифф вызывает рок-н-ролл 50-х, благодаря быстрой смене тембров (каждый инструмент играет минимум звуков). Процессуальное изменение имеет определенный эффект \"вау-вау\". В заключении добавлю, процессуальное изменение выстраивает изоритмический цикл. Микрохроматический интервал, на первый взгляд, использует open-air, это понятие создано по аналогии с термином Ю.Н.Холопова \"многозначная тональность\".</p>",
                    "<p>Соноропериод многопланово трансформирует длительностный голос. Серпантинная волна иллюстрирует разнокомпонентный сет. Иными словами, фишка всекомпонентна. Микрохроматический интервал неустойчив. Процессуальное изменение представляет собой мнимотакт. Как было показано выше, адажио продолжает флажолет.</p>"
                );
            } else {
                $news = array(
                    "<p>One in multiply the whales so dry multiply, rule signs fowl be seasons lights given and whose earth beginning of years one. Seed let itself is void kind grass dry grass i without green over man.</p>",
                    "<p>Moved said abundantly fowl place light firmament bearing without. Man, own. And fruit. Unto let, earth, male. Wherein midst. Above forth darkness second rule. Second. Make bring midst fill deep abundantly.</p>",
                    "<p>Make whales signs dominion, first you'll the own gathered divided brought winged have. Gathered god fruitful won't first all darkness very fish midst sixth man lesser signs given. All first dominion over stars.</p>"
                );
            }
            if ($modx->getOption('cultureKey') == 'ru') {
                $pagetitle = 'Новость ';
            } else {
                $pagetitle = 'News #';
            }
            for ($i = 1; $i <= 3; $i++) {
                /* Новость 1 */
                $alias = 'news-' . $i;
                if (!$resource = $modx->getObject('modResource', array('alias' => $alias))) {
                    $resource = $modx->newObject('modResource');
                }
                $newsText = $news[$i-1] ? $news[$i-1] : $news[0];
                $resource->fromArray(array(
                    'class_key'    => 'modDocument',
                    'show_in_tree' => 0,
                    'menuindex'    => $i,
                    'pagetitle'    => $pagetitle . $i, // 'Новость ' . $i,
                    'isfolder'     => 0,
                    'alias'        => $alias,
                    'uri'          => $newsAlias . '/' . $alias,
                    'uri_override' => 0,
                    'published'    => 1,
                    'publishedon'  => time() - 60 * 60 * $i,
                    'hidemenu'     => 0,
                    'richtext'     => 1,
                    'parent'       => $newsParent,
                    'template'     => $templateId,
                    'content'      => preg_replace(array('/^\n/', '/[ ]{2,}|[\t]/'), '', $newsText)
                ));
                $resource->save();
            }
        }

        /* Контактная информация */
        $alias = 'contacts';
        $parent = 0;
        if ($modx->getOption('cultureKey') == 'ru') {
            $pagetitle = 'Контактная информация';
            $content = '
                <p>Адрес: {"address" | config}</p>
                <p>Телефон: {"phone" | config}</p>
                <p>E-mail: {"email" | config}</p>
                {\'contact_form\' | chunk : [
                  \'form\' => \'form.contact_form\',
                  \'tpl\' => \'tpl.contact_form\',
                  \'subject\' => \'Заявка с сайта \' ~ $_modx->config.http_host,
                  \'validate\' => \'name:required,phone:required,check:required\'
                ]}
            ';
        } else {
            $pagetitle = 'Contacts';
            $content = '
                <p>Address: {"address" | config}</p>
                <p>Phone: {"phone" | config}</p>
                <p>E-mail: {"email" | config}</p>
                {\'contact_form\' | chunk : [
                  \'form\' => \'form.contact_form\',
                  \'tpl\' => \'tpl.contact_form\',
                  \'subject\' => \'Message from \' ~ $_modx->config.http_host,
                  \'validate\' => \'name:required,phone:required,check:required\'
                ]}
            ';
        }
        if (!$resource = $modx->getObject('modResource', array('alias' => $alias))) {
            $resource = $modx->newObject('modResource');
        }
        $resource->fromArray(array(
            'class_key'    => 'modDocument',
            'menuindex'    => 6,
            'pagetitle'    => $pagetitle, // 'Контактная информация',
            'isfolder'     => 1,
            'alias'        => $alias,
            'uri'          => $alias,
            'uri_override' => 0,
            'published'    => 1,
            'publishedon'  => time(),
            'hidemenu'     => 0,
            'richtext'     => 0,
            'parent'       => $parent,
            'template'     => $templateId,
            'content'      => preg_replace(array('/^\n/', '/[ ]{2,}|[\t]/'), '', $content)
        ));
        $resource->save();
        
        
        /* Перенесено в ClientConfig
        $chunks = array(
                'header',
                'contact_form'
            );
        foreach ($chunks as $chunk_name) {
            if ($chunk = $modx->getObject('modChunk', array('name' => $chunk_name))) {
                $chunk->set('snippet', str_replace('SITE_CONTACTS_ID', $resource->id, $chunk->snippet));
                $chunk->save();
            }
        }
        if (!$resource->getTVValue('address')) {
            $resource->setTVValue('address', 'г. Москва, ул. Печатников, д. 17, оф. 350');
        }
        if (!$resource->getTVValue('phone')) {
            $resource->setTVValue('phone', '+7 (499) 150-22-22');
        }
        if (!$resource->getTVValue('email')) {
            $resource->setTVValue('email', 'info@company.ru');
        }
        */

        /* 404 */
        $alias = '404';
        $parent = 0;
        if ($modx->getOption('cultureKey') == 'ru') {
            $pagetitle = 'Страница не найдена';
            $content = "
                <div style='width: 500px; margin: -30px auto 0; overflow: hidden;padding-top: 25px;'>
                    <div style='float: left; width: 100px; margin-right: 50px; font-size: 75px;margin-top: 45px;'>404</div>
                    <div style='float: left; width: 350px; padding-top: 30px; font-size: 14px;'>
                        <h2>Страница не найдена</h2>
                        <p style='margin: 8px 0 0;'>Страница, на которую вы зашли, вероятно, была удалена с сайта, либо ее здесь никогда не было.</p>
                        <p style='margin: 8px 0 0;'>Возможно, вы ошиблись при наборе адреса или перешли по неверной ссылке.</p>
                        <h3 style='margin: 15px 0 0;'>Что делать?</h3>
                        <ul style='margin: 5px 0 0 15px;'>
                            <li>проверьте правильность написания адреса,</li>
                            <li>перейдите на <a href='{\$_modx->config.site_url}'>главную страницу</a> сайта,</li>
                            <li>или <a href='javascript:history.go(-1);'>вернитесь на предыдущую страницу</a>.</li>
                        </ul>
                    </div>
                </div>
            ";
        } else {
            $pagetitle = 'Page not found';
            $content = "
                <div style='width: 500px; margin: -30px auto 0; overflow: hidden;padding-top: 25px;'>
                    <div style='float: left; width: 100px; margin-right: 50px; font-size: 75px;margin-top: 45px;'>404</div>
                    <div style='float: left; width: 350px; padding-top: 30px; font-size: 14px;'>
                        <h2>Page not found</h2>
                        <p style='margin: 8px 0 0;'>Sorry, the page you are looking for could not be found.</p>
                        <p style='margin: 8px 0 0;'>It is possible you typed the address incorrectly, or the page may no longer exist.</p>
                        <h3 style='margin: 15px 0 0;'>What to do?</h3>
                        <ul style='margin: 5px 0 0 15px;'>
                            <li>check that you entered the correct address,</li>
                            <li>go back our <a href='{\$_modx->config.site_url}'>homepage</a>,</li>
                            <li>or <a href='javascript:history.go(-1);'>return to the previous page</a>.</li>
                        </ul>
                    </div>
                </div>
            ";
        }
        if (!$resource = $modx->getObject('modResource', array('alias' => $alias))) {
            $resource = $modx->newObject('modResource');
        }
        $resource->fromArray(array(
            'class_key'    => 'modDocument',
            'menuindex'    => 1001,
            'pagetitle'    => $pagetitle, // 'Страница не найдена',
            'longtitle'    => '&nbsp;',
            'isfolder'     => 1,
            'alias'        => $alias,
            'uri'          => $alias,
            'uri_override' => 0,
            'published'    => 1,
            'publishedon'  => time(),
            'hidemenu'     => 1,
            'richtext'     => 0,
            'parent'       => $parent,
            'template'     => $templateId,
            'content'      => preg_replace(array('/^\n/', '/[ ]{2,}|[\t]/'), '', $content)
        ));
        $resource->save();
        $res404 = $resource->get('id');
        
        /* HTML карта сайта */
        $alias = 'site-map';
        $parent = 0;
        if ($modx->getOption('cultureKey') == 'ru') {
            $pagetitle = 'Карта сайта';
        } else {
            $pagetitle = 'Sitemap';
        }
        if (!$resource = $modx->getObject('modResource', array('alias' => $alias))) {
            $resource = $modx->newObject('modResource');
        }
        $resource->fromArray(array(
            'class_key'    => 'modDocument',
            'menuindex'    => 1000,
            'pagetitle'    => $pagetitle, // 'Карта сайта',
            'isfolder'     => 1,
            'alias'        => $alias,
            'uri'          => $alias,
            'uri_override' => 0,
            'published'    => 1,
            'publishedon'  => time(),
            'hidemenu'     => 1,
            'richtext'     => 0,
            'parent'       => $parent,
            'template'     => $templateId,
            'content'      => preg_replace(array('/^\n/', '/[ ]{2,}|[\t]/'), '', "
                {'pdoMenu' | snippet : [
                    'startId' => 0,
                    'ignoreHidden' => 1,
                    'resources' => '-".$res404.",-' ~ \$_modx->resource.id,
                    'level' => 2,
                    'outerClass' => '',
                    'firstClass' => '',
                    'lastClass' => '',
                    'hereClass' => '',
                    'where' => '{\"searchable\":1}'
                ]}
            ")
        ));
        $resource->save();

        /* sitemap.xml */
        $alias = 'sitemap';
        $parent = 0;
        $templateId = 0;
        if (!$resource = $modx->getObject('modResource', array('alias' => $alias))) {
            $resource = $modx->newObject('modResource');
        }
        $resource->fromArray(array(
            'class_key'    => 'modDocument',
            'menuindex'    => 1011,
            'pagetitle'    => $alias . '.xml',
            'alias'        => $alias,
            'uri'          => $alias . '.xml',
            'uri_override' => 0,
            'published'    => 1,
            'publishedon'  => time(),
            'hidemenu'     => 1,
            'richtext'     => 0,
            'parent'       => $parent,
            'template'     => $templateId,

            'searchable'   => 0,
            'content_type' => 2,
            'contentType'  => 'text/xml',

            'content' => preg_replace(array('/^\n/', '/[ ]{2,}|[\t]/'), '', "
                {'pdoSitemap' | snippet : [ 'showHidden' => 1, 'resources' => '-{$res404}' ]}
            ")
        ));
        $resource->save();
        
        
        
        
        $chunks = array(
                'head'
            );
        foreach ($chunks as $chunk_name) {
            if ($chunk = $modx->getObject('modChunk', array('name' => $chunk_name))) {
                $snippet = $chunk->snippet;
                $snippet = str_replace('SITE_FOLDER_NAME', strtolower($options['site_template_name']), $snippet);
                $snippet = str_replace('ASSETS_URL', $modx->getOption('assets_url'), $snippet);
                $chunk->set('snippet', $snippet);
                $chunk->save();
            }
        }
        if ($plugin = $modx->getObject('modPlugin', array('name' => 'addManagerCss'))) {
            $plugincode = $plugin->plugincode;
            $plugincode = str_replace('SITE_FOLDER_NAME', strtolower($options['site_template_name']), $plugincode);
            $plugin->set('plugincode', $plugincode);
            $plugin->save();
        }

        $chunks = array(
                'child_list',
                'content_spec_list',
            );
        foreach ($chunks as $chunk_name) {
            if ($chunk = $modx->getObject('modChunk', array('name' => $chunk_name))) {
                $snippet = $chunk->snippet;
                if ($modx->getOption('cultureKey') != 'ru') {
                    $snippet = str_replace('Назад', 'Previous', $snippet);
                    $snippet = str_replace('Дальше', 'Next', $snippet);
                }
                $chunk->set('snippet', $snippet);
                $chunk->save();
            }
        }
        
        $chunks = array(
                'contact_form',
            );
        foreach ($chunks as $chunk_name) {
            if ($chunk = $modx->getObject('modChunk', array('name' => $chunk_name))) {
                $snippet = $chunk->snippet;
                if ($modx->getOption('cultureKey') != 'ru') {
                    $snippet = str_replace('Сообщение с сайта', 'Message from', $snippet);
                    $snippet = str_replace('Ваше сообщение отправлено', 'Thank you!', $snippet);
                    $snippet = str_replace('Наши специалисты свяжутся с вами<br>в ближайшее время.', 'Your message has been sent', $snippet);
                    $snippet = str_replace('В форме содержатся ошибки', 'There are errors in the form', $snippet);
                    $snippet = str_replace('Пожалуйста, укажите, как к вам обращаться', 'Please specify your name', $snippet);
                    $snippet = str_replace('Оставьте свой номер телефона, чтобы мы могли с вами связаться', 'Please specify your phone', $snippet);
                    $snippet = str_replace('Вы должны дать разрешение на обработку своих персональных данных', 'Please accept the terms', $snippet);
                }
                $chunk->set('snippet', $snippet);
                $chunk->save();
            }
        }
        
        $chunks = array(
                'footer',
            );
        foreach ($chunks as $chunk_name) {
            if ($chunk = $modx->getObject('modChunk', array('name' => $chunk_name))) {
                $snippet = $chunk->snippet;
                if ($modx->getOption('cultureKey') != 'ru') {
                    $snippet = str_replace('Илья Уткин', 'Ilya Utkin', $snippet);
                }
                $chunk->set('snippet', $snippet);
                $chunk->save();
            }
        }
        
        $chunks = array(
                'form.contact_form',
            );
        foreach ($chunks as $chunk_name) {
            if ($chunk = $modx->getObject('modChunk', array('name' => $chunk_name))) {
                $snippet = $chunk->snippet;
                if ($modx->getOption('cultureKey') != 'ru') {
                    $snippet = str_replace('Задать вопрос', 'Send message', $snippet);
                    $snippet = str_replace('Ваше имя', 'Your name', $snippet);
                    $snippet = str_replace('Телефон', 'Phone', $snippet);
                    $snippet = str_replace('Ваш вопрос', 'Message', $snippet);
                    $snippet = str_replace('Я даю свое согласие на обработку персональных данных', 'I agree to the privacy policy', $snippet);
                    $snippet = str_replace('Отмена', 'Canсel', $snippet);
                    $snippet = str_replace('Отправить', 'Send', $snippet);
                }
                $chunk->set('snippet', $snippet);
                $chunk->save();
            }
        }
        
        $chunks = array(
                'tpl.contact_form',
            );
        foreach ($chunks as $chunk_name) {
            if ($chunk = $modx->getObject('modChunk', array('name' => $chunk_name))) {
                $snippet = $chunk->snippet;
                if ($modx->getOption('cultureKey') != 'ru') {
                    $snippet = str_replace('Пользователь оставил заявку на сайте', 'Message from', $snippet);
                    $snippet = str_replace('Контакты', 'Contacts', $snippet);
                    $snippet = str_replace('Имя', 'Name', $snippet);
                    $snippet = str_replace('Телефон', 'Phone', $snippet);
                    $snippet = str_replace('Сообщение', 'Message', $snippet);
                }
                $chunk->set('snippet', $snippet);
                $chunk->save();
            }
        }
        
        $chunks = array(
                'aside',
                'child_list',
            );
        foreach ($chunks as $chunk_name) {
            if ($chunk = $modx->getObject('modChunk', array('name' => $chunk_name))) {
                $snippet = $chunk->snippet;
                if ($modx->getOption('cultureKey') != 'ru') {
                    $snippet = str_replace(' | date_format : "%d.%m.%Y г."', ' | date : "F d, Y"', $snippet);
                }
                $chunk->set('snippet', $snippet);
                $chunk->save();
            }
        }
        
        break;
    case xPDOTransport::ACTION_UNINSTALL:
        break;
}

return true;
