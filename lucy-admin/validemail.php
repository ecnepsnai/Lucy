<?php

// Validate Email function
function validEmail($email) {
   // Email is assumed as true, needs to be proven false.
   $isValid = true;

   // Breaking down the address to local @ domain.tld
   $atIndex = strrpos($email, "@");

   // If the email is missing the @
   if (is_bool($atIndex) && !$atIndex) {
      $isValid = false;
   } else {
      $domain = substr($email, $atIndex+1);
      $local = substr($email, 0, $atIndex);
      $localLen = strlen($local);
      $domainLen = strlen($domain);
      if ($localLen < 1 || $localLen > 64) {
         $isValid = false;
      }
      else if ($domainLen < 1 || $domainLen > 255) {
         $isValid = false;
      }
      else if ($local[0] == '.' || $local[$localLen-1] == '.') {
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $local)) {
         $isValid = false;
      }
      else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $domain)) {
         $isValid = false;
      } else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local))) {
         if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$local))) {
            $isValid = false;
         }
      }

      // Check the DNS for the domain.
      if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A"))) {
         $isValid = false;
      }

      // Loop through a list of known spam domains.
      $spam_Domains = array('datingservices.com', 'ukr.net', 'giza.cc', 'sibmail.com', 'sanna.cc', 'boot.info', '301drush.info', '301azn.info', 'bumpymail.com', 'centermail.com', 'centermail.net', 'discardmail.com', 'dodgeit.com', 'e4ward.com', 'emailias.com', 'front14.org', 'ghosttexter.de', 'jetable.net', 'jetable.org', 'kasmail.com', 'link2mail.net', 'mail333.com', 'mailblocks.com', 'maileater.com', 'mailexpire.com', 'mailinator.com', 'mailmoat.com', 'mailnull.com', 'mailshell.com', 'mailzilla.com', 'messagebeamer.de', 'mytrashmail.com', 'nervmich.net', 'netmails.net', 'netzidiot.de', 'nurfuerspam.de', 'pookmail.com', 'portsaid.cc', 'privacy.net', 'punkass.com', 'shortmail.net', 'sneakemail.com', 'sofort-mail.de', 'sogetthis.com', 'spam.la', 'spambob.com', 'spambob.net', 'spambob.org', 'spamex.com', 'spamgourmet.com', 'spamhole.com', 'spaminator.de', 'spammotel.com', 'spamtrail.com', 'tempinbox.com', 'trash-mail.de', 'trashmail.net', '35.ru', '3fn.net', '3gcare.com', '56.com', 'a.org.ua', 'abha.cc', 'adtoad.com', 'agadir.cc', 'ahsa.ws', 'ajman.cc', 'ajman.us', 'ajman.ws', 'albaha.cc', 'alex4all.com', 'alexandria.cc', 'algerie.cc', 'allspaces.com', 'alriyadh.cc', 'amman.cc', 'aqaba.cc', 'arar.ws', 'aswan.cc', 'baalbeck.cc', 'bahraini.cc', 'banha.cc', 'beep.ru', 'bizerte.cc', 'bk.ru', 'bobidiko.com', 'bonbon.net', 'buffnet.net', 'buraydah.cc', 'cameroon.cc', 'cashette.com', 'chat.ru', 'ciber.com', 'cnuninet.com', 'crestorbanda.net', 'dbzmail.com', 'dhahran.cc', 'dhofar.cc', 'djibouti.cc', 'dominican.cc', 'eastday.com', 'email.ru', 'e-mail.ru', 'emails.ru', 'e-mails.ru', 'eritrea.cc', 'ezmail.ru', 'falasteen.cc', 'freemail.ru', 'fromru.com', 'fromru.ru', 'front.ru', 'fujairah.cc', 'fujairah.us', 'fujairah.ws', 'gabes.cc', 'gafsa.cc', 'gala.net', 'gals4all.com', 'gamebox.net', 'gawab.com', 'gmx.net', 'go.ru', 'guinea.cc', 'hamra.cc', 'harvestfee.com', 'hasakah.com', 'hebron.tv', 'homs.cc', 'hotbox.ru', 'hotmail.ru', 'hotpop.com', 'ibra.cc', 'i-connect.ru', 'ifrance.com', 'imail.ru', 'inbox.ru', 'irbid.ws', 'ismailia.cc', 'isuisse.com', 'jadida.cc', 'jadida.org', 'jerash.cc', 'jizan.cc', 'jouf.cc', 'kairouan.cc', 'karak.cc', 'khaimah.cc', 'khartoum.cc', 'khobar.cc', 'khv.ru', 'kuwaiti.tv', 'kyrgyzstan.cc', 'land.ru', 'latakia.cc', 'lcgrowth.com', 'lebanese.cc', 'libero.it', 'list.ru', 'lubnan.cc', 'lubnan.ws', 'madinah.cc', 'maghreb.cc', 'mail.by', 'mail15.com', 'mail2k.ru', 'mailgate.ru', 'mailpuppy.com', 'manama.cc', 'mansoura.tv', 'marrakesh.cc', 'mascara.ws', 'masterhost.ru', 'meknes.cc', 'muscat.tv', 'muscat.ws', 'nabeul.cc', 'nabeul.info', 'nablus.cc', 'nador.cc', 'najaf.cc', 'narol.ru', 'nefigasebe.com', 'newmail.ru', 'nextmail.ru', 'nightmail.ru', 'nm.ru', 'null.com', 'nxt.ru', 'omani.ws', 'omdurman.cc', 'online.ru', 'oran.cc', 'oued.org', 'oujda.biz', 'oujda.cc', 'pakistani.ws', 'palmyra.cc', 'palmyra.ws', 'phreaker.net', 'pisem.net', 'pochta.ru', 'pochtamt.ru', 'qassem.cc', 'quds.cc', 'rabat.cc', 'rafah.cc', 'ramallah.cc', 'rambler.ru', 'safat.us', 'safat.ws', 'sahyog.com', 'salalah.cc', 'sanaa.cc', 'scut.edu.cn', 'seeb.cc', 'sexmagnet.com', 'sfax.ws', 'sharm.cc', 'sify.com', 'sina.com', 'sinai.cc', 'siria.cc', 'smtp.ru', 'sousse.cc', 'spb.ru', 'sudanese.cc', 'suez.cc', 'supermail.ru', 'tabouk.cc', 'tajikistan.cc', 'tangiers.cc', 'tanta.cc', 'tayef.cc', 'teghhu.com', 'terrgfhu.com', 'terru.com', 'tetouan.cc', 'timor.cc', 'tma.ru', 'toughguy.net', 'tunisian.cc', 'tut.by', 'tyt.by', 'ua.fm', 'ukrtop.com', 'urdun.cc', 'usa.com', 'valentinno.com', 'vipmail.ru', 'wwwomen.ru', 'xoxma.com', 'yanbo.cc', 'yandex', 'yemeni.cc', 'yuhknow.com', 'yunus.cc', 'zagazig.cc', 'zambia.cc', 'zarqa.cc', 'zmail.ru', 'zonnewater.net', 'advertfast.com', 'globalsources.com', 'tradedoubling.co.uk', 'wasphawk.ru', 'cute-boys.orga.cc', 'myway.com', 'inbox.com', 'brainyonline.info', 'burnacouplemore.com', 'alertonline.info', 'onlinehoster.com', 'abilityonline.info', 'camefromblue.info', 'spambob', 'fakeinformation.com', 'wuzup.net', 'sriaus.com', 'gold-profits.info', 'blida.info', 'oued.info', 'safat.biz', 'safat.info', 'salmiya.biz', 'au.ru', 'halyava.ru', 'id.ru', 'notmail.ru', 'ok.ru', 'ru.ru', 'sendmail.ru', 'yandex.ru', 'gomail.com.ua', '6url.com', 'gishpuppy.com', 'greensloth.com', 'spamday.com', 'xents.com', 'zoemail.com', 'freestuffo2.com', 'freestuffo1.com', 'freestuffo3.com', 'freestuffo4.com', 'cash.com');
      foreach($spam_Domains as $spam_Domain){
         if($domain == $spam_Domain){
            $isValid = false;
         }
      }
   }
   return $isValid;
}