
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes React and other helpers. It's a great starting point while
 * building robust, powerful web applications using React + Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh React component instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

var React = require("react");
var ReactDOM = require('react-dom');
import Example from "./components/Example";
import Aggrid from "./components/Aggrid";
import "ag-grid-enterprise";
import {LicenseManager} from "ag-grid-enterprise/main";
LicenseManager.setLicenseKey("ag-Grid_Evaluation_License_Key_Not_for_Production_100Devs24_January_2018__MTUxNjc1MjAwMDAwMA==9c12b8b51496ab050072d42a80743360");
import "ag-grid/dist/styles/ag-grid.css";
import "ag-grid/dist/styles/theme-fresh.css";
import {Router, Route, IndexRoute, browserHistory} from 'react-router';
import {} from './components/api';

ReactDOM.render(
    <Router history={browserHistory}>
        <Route path="/showdatabase" component={Aggrid}>
        </Route>
    </Router>, document.getElementById('container')
);
