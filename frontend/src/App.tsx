import React from "react";
import "./hydrofiel.css";
import Footer from "./Footer";
import Banner from "./layout/Banner";
import Menu from "./layout/Menu";
import Swimming from "./staticPages/Swimming";
import Calendar from "./layout/Calendar";
import { BrowserRouter as Router, Switch, Route } from "react-router-dom";

const App: React.FC = () => {
	return (
		<div className="App">
			<Router>
			<Menu />
			<Banner />
			<div className="container" style={{ paddingTop: 10, paddingBottom: 10 }}>
				<div className="container-fluid" id="content">
						<Switch>
							<Route path="/calendar">
								<Calendar />
							</Route>
							<Route path="/">
								<Swimming />
							</Route>
						</Switch>
				</div>
			</div>
			<Footer />
			</Router>
		</div>
	);
};

export default App;
