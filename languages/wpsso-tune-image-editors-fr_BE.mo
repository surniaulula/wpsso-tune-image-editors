��    8      �  O   �      �  `   �  Z   :  <   �  R   �  ;   %  �   a  X   �  �   J  �   �  h        �     �  X   �  T   T	  
   �	     �	  8   �	  (   �	  Z   
  )   z
  n   �
  {     K   �  w   �  a   S  |   �  O   2     �     �  x   �     *  :   =  "   x     �  $   �      �  )   �     #     B      `  .   �  )   �     �     �          6  !   T      v     �     �     �     �  {   
     �  '   �  �  �  o   {  c   �  H   O  o   �  X     �   a  f     �   t  �   +  q   �     b     n  q   v  a   �     J     V  ;   c  &   �  i   �  '   0  �   X  �   �  I   h  �   �  j   J  �   �  \   I     �  &   �  �   �     �  :   �     �     �     �          $     ?     O     e  "   }  (   �     �     �     �                 &      <      J      \      s   q   �   
   �   &   !     0         1   7   (                                     3                 2          "         .                
                       +   4   '   /   &      )      5          *                %       #      !          6              8   $      -                 ,               	    %1$s option for ImageMagick is enabled, but WordPress is using the %2$s library for %3$s images. %s hooks the WordPress 'image_make_intermediate_size' filter to adjust and sharpen images. A sharpening sigma value between 0.5 and 1.0 is recommended. A value of 0 may be desirable to retain fine skin details in portrait photographs. Apply image adjustments for resized %1$s images using %2$s. By default, WordPress uses the ImageMagick editor first (provided the PHP "imagick" extension is loaded), and uses the GD editor as a fallback. By default, WordPress uses the ImageMagick editor first, as editor #1 in the '%s' array. Higher values (closer to 1) allow sharpening only in high-contrast regions, like strong edges, while leaving low-contrast areas unaffected. If the WordPress %1$s editor is available, but the PHP "%2$s" extension is not loaded, contact your hosting provider and ask to have the PHP "%2$s" extension installed. Improves the appearance of WordPress images for better click through rates from social and search sites. JS Morisset Loaded Lower values (closer to 0) allow sharpening in relatively smoother regions of the image. Minimum contrast required for a pixel to be considered an edge pixel for sharpening. Not loaded Not used Status of the %1$s editor in the WordPress '%2$s' array. Status of the PHP "%s" extension module. The %1$s add-on requires %2$s version %3$s or newer (version %4$s is currently installed). The %1$s add-on requires the %2$s plugin. The amount (ie. strength) of the sharpening effect. A larger value increases the contrast of sharpened pixels. The best sharpening radius depends on the resized image resolution, and for this reason, the recommended value is 0 (auto). The default value is 1.0, and the recommended range is between 0.8 and 1.2. The resized image compression quality as a positive integer value between 1 and 100. The recommended value is 90 to 95. The sharpening radius is an integer value, generally one to two times the sharpening sigma value. The sharpening sigma can be any floating-point value, from 0.1 for almost no sharpening, to 3 or more for severe sharpening. This option allows you to select a different default editor list for WordPress. Used as editor #%d WPSSO Tune WP Image Editors You can change the filter priority to process images before/after other image processing plugins or custom filter hooks. https://wpsso.com/ https://wpsso.com/extend/plugins/wpsso-tune-image-editors/ lib file descriptionImage Editors metabox tabImageMagick metabox titlePHP Extension Settings metabox titleWordPress Settings option comment(recommended %1$s to %2$s) option comment(recommended 0) option labelAdjust %s Images option labelCompression Quality option labelDefault WordPress Image Editor(s) option labelImage Adjust Filter Priority option labelPHP %s Extension option labelSharpening Amount option labelSharpening Radius option labelSharpening Sigma option labelSharpening Threshold option labelWordPress %s Editor option valueGD Only option valueGD and ImageMagick option valueImageMagick Only option valueImageMagick and GD plugin descriptionImproves the appearance of WordPress images for better click through rates from social and search sites. plugin nameWPSSO Core plugin nameWPSSO Tune WP Image Editors Project-Id-Version: WPSSO Tune Image Editors 3.1.0
Report-Msgid-Bugs-To: https://wordpress.org/support/plugin/wpsso-tune-image-editors
PO-Revision-Date: 2023-11-03 21:32-0700
Last-Translator: JS Morisset <jsm@surniaulula.com>
Language-Team: 
Language: fr_FR
MIME-Version: 1.0
Content-Type: text/plain; charset=UTF-8
Content-Transfer-Encoding: 8bit
Plural-Forms: nplurals=2; plural=(n > 1);
X-Generator: Poedit 3.4.1
X-Poedit-Basepath: .
 L'option %1$s pour ImageMagick est activée, mais WordPress utilise la bibliothèque %2$s pour les images %3$s. %s accroche le filtre WordPress 'image_make_intermediate_size' pour ajuster et affûter les images. Une valeur sigma d'affûtage comprise entre 0.5 et 1.0 est recommandée. Une valeur de 0 peut être souhaitable pour conserver les détails fins de la peau dans les photos de portrait. Appliquez les réglages d'image pour les images %1$s redimensionnées à l'aide de %2$s. Par défaut, WordPress utilise d'abord l'éditeur ImageMagick (à condition que l'extension PHP "imagick" soit chargée), et utilise l'éditeur GD comme solution de repli. Par défaut, WordPress utilise d'abord l'éditeur ImageMagick comme éditeur # 1 dans le tableau '%s'. Des valeurs plus élevées (plus proches de 1) permet d'affûter uniquement dans les zones à contraste élevée, comme les bordures fortes, et ignorer les zones à faible contraste. Si l'éditeur de WordPress %1$s est disponible, mais que l'extension PHP "%2$s" n'est pas chargée, contactez votre fournisseur d'hébergement et demandez l'installation de l'extension PHP "%2$s". Améliore l'apparence des images WordPress pour de meilleurs taux de clics sur les sites sociaux et de recherche. JS Morisset Chargé Les valeurs inférieures (plus proches de 0) permettent d'affûter les zones relativement plus lisses de l'image. Contraste minimal requis pour qu'un pixel soit considéré comme un pixel de contour à affûter. Non chargé Non utilisé Statut de l'éditeur %1$s dans le tableau WordPress '%2$s'. Statut du module d'extension PHP "%s". L'ajout %1$s nécessite %2$s version %3$s ou plus récente (la version %4$s est installée actuellement). L'ajout %1$s requiert l'extension %2$s. La quantité (c'est-à-dire la force) de l'effet d'affûtage. Une valeur plus grande augmente le contraste des pixels affûtés. Le meilleur rayon d'affûtage dépend de la résolution de l'image redimensionnée et, pour cette raison, la valeur recommandée est 0 (auto). La valeur par défaut est 1.0 et la plage recommandée est de 0.8 à 1.2. La qualité de compression de l'image redimensionnée est une valeur entière positive comprise entre 1 et 100. La valeur recommandée est de 90 à 95. Le rayon d'affûtage est une valeur entière, généralement une à deux fois la valeur sigma d'affûtage. Le sigma d'affûtage peut être n'importe quelle valeur en flottante, de 0.1 pour presque pas d'affûtage, à 3 ou plus pour un affûtage sévère. Cette option vous permet de sélectionner une autre liste d'éditeurs WordPress par défaut. Utilisé comme éditeur #%d WPSSO Ajuster les éditeurs d'image WP Vous pouvez modifier la priorité du filtre pour traiter les images avant/après d'autres extensions de traitement d'image ou crochets de filtre personnalisés. https://wpsso.com/ https://wpsso.com/extend/plugins/wpsso-tune-image-editors/ Éditeurs d'image ImageMagick Réglages d'extension PHP Réglages WordPress (recommandé %1$s à %2$s) (recommandé 0) Ajuster les images %s Qualité de compression Éditeur(s) d'image WP par défaut Priorité du filtre d'ajustement d'image Extension PHP %s Quantité d'affûtage Rayon d'affûtage Sigma d'affûtage Seuil d'affûtage Éditeur WordPress %s GD uniquement GD et ImageMagick ImageMagick uniquement ImageMagick et GD Améliore l'apparence des images WordPress pour de meilleurs taux de clics sur les sites sociaux et de recherche. WPSSO Core WPSSO Ajuster les éditeurs d'image WP 