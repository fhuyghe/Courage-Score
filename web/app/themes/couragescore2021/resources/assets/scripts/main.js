// import external dependencies
import 'jquery';

// Import everything from autoload
import './autoload/**/*'

// import local dependencies
import Router from './util/Router';
import common from './routes/common';
import home from './routes/home';
import aboutUs from './routes/about';
import singlePeople from './routes/singlePeople';
import allRepresentatives from './routes/allRepresentatives';
import templateCustomScore from './routes/customScore';
import bills from './routes/bills';

/** Populate Router instance with DOM routes */
const routes = new Router({
  // All pages
  common,
  // Home page
  home,
  // About Us page, note the change from about-us to aboutUs.
  aboutUs,
  singlePeople,
  allRepresentatives,
  bills,
  templateCustomScore,
});

// Load Events
jQuery(document).ready(() => routes.loadEvents());
