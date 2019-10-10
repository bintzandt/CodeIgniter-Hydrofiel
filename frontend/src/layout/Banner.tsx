import React from "react";

const Banner = () => {
	return (
		<div className="banner">
			<div className="banner-info" id="info" style={{
				position: "relative",
				top: "50%",
				transform: "perspective(1px) translateY(-50%)",
				textAlign: "center",
			}}>
				<h2>N.S.Z.&amp;W.V. Hydrofiel</h2>
			</div>
		</div>
	);
};

export default Banner;
