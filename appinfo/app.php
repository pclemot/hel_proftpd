<?php

namespace OCA\Hel_Proftpd;

\OCP\Util::connectHook('OC_User', 'post_createUser',  'OCA\Hel_Proftpd\Hooks', 'createUser');
\OCP\Util::connectHook('OC_User', 'post_deleteUser',  'OCA\Hel_Proftpd\Hooks', 'deleteUser');
\OCP\Util::connectHook('OC_User', 'post_setPassword', 'OCA\Hel_Proftpd\Hooks', 'updateUser');
