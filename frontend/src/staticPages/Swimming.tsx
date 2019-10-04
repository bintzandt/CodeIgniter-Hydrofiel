import React, { useEffect, useState } from "react";
import axios from "axios";

const Swimming = () => {
	const [ data, updatePage ] = useState("");
	useEffect(() => {
		const fetchPage = async () => {
			const result = await axios(
				"https://www.hydrofiel.nl",
				{ headers: { crossDomain: true } },
			);
			updatePage( result.data );
		};
		fetchPage();
	});
	return (
		<div dangerouslySetInnerHTML={{__html: data}}></div>
	);
};
export default Swimming;
