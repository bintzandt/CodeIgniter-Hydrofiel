import React from "react";
import "./hydrofiel.css";
import Footer from "./Footer";
import Banner from "./layout/Banner";
import Menu from "./layout/Menu";
import Swimming from "./staticPages/Swimming";

const state = {
	pages: {
		zwemmen: <Swimming />,
	},
};

const App: React.FC = () => {
	return (
		<div className="App">
			<Menu />
			<Banner />
			<div className="container" style={{ paddingTop: 10, paddingBottom: 10 }}>
				<div className="container-fluid" id="content">
					{state.pages.zwemmen}
				</div>
			</div>
			<Footer />
		</div>
	);
};

export default App;
