<?php
/**
 * Shows the admin search frontend for FAQs
 *
 * PHP Version 5.2
 *
 * The contents of this file are subject to the Mozilla Public License
 * Version 1.1 (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 *
 * Software distributed under the License is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License.
 * 
 * @category  phpMyFAQ
 * @package   Administration
 * @author    Thorsten Rinne <thorsten@phpmyfaq.de>
 * @copyright 2011 phpMyFAQ Team
 * @license   http://www.mozilla.org/MPL/MPL-1.1.html Mozilla Public License Version 1.1
 * @link      http://www.phpmyfaq.de
 * @since     2011-09-29
 */

if (!defined('IS_VALID_PHPMYFAQ')) {
    header('Location: http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']));
    exit();
}

printf("<header><h2>%s</h2><header>\n", $PMF_LANG['ad_menu_searchfaqs']);

if ($permission['editbt'] || $permission['delbt']) {

    $searchcat  = PMF_Filter::filterInput(INPUT_POST, 'searchcat', FILTER_VALIDATE_INT);
    $searchterm = PMF_Filter::filterInput(INPUT_POST, 'searchterm', FILTER_SANITIZE_STRIPPED);

    // (re)evaluate the Category object w/o passing the user language
    $category = new PMF_Category($current_admin_user, $current_admin_groups, false);
    $category->transform(0);

    // Set the Category for the helper class
    $helper = PMF_Helper_Category::getInstance();
    $helper->setCategory($category);

    $category->buildTree();
    
    $linkVerifier = new PMF_Linkverifier($user->getLogin());
?>

    <form action="?action=view" method="post">
    <fieldset>
        <legend><?php print $PMF_LANG["msgSearch"]; ?></legend>

        <p>
            <label><?php print $PMF_LANG["msgSearchWord"]; ?>:</label>
            <input type="text" name="searchterm" size="50" value="<?php print $searchterm; ?>" autofocus="autofocus" />
            <?php if ($linkVerifier->isReady() == true): ?>
            <br />
            <input type="checkbox" name="linkstate" value="linkbad" />
            <?php print $PMF_LANG['ad_linkcheck_searchbadonly']; ?>
            <?php endif; ?>
        </p>
        <p>
            <label><?php print $PMF_LANG["msgCategory"]; ?>:</label>
            <select name="searchcat">
                <option value="0"><?php print $PMF_LANG["msgShowAllCategories"]; ?></option>
                <?php print $helper->renderCategoryOptions($searchcat); ?>
            </select>
        </p>
        <p>
            <input class="submit" type="submit" name="submit" value="<?php print $PMF_LANG["msgSearch"]; ?>" />
        </p>
    </fieldset>
    </form>

<?php
} else {
    print $PMF_LANG['err_NotAuth'];
}