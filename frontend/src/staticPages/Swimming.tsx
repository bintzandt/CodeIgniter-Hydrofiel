import React, { useEffect, useState } from "react";
import axios from "axios";

const Swimming = () => {
	const [ data, updatePage ] = useState("");
	useEffect(() => {
		const fetchPage = async () => {
			const result = await axios(
				"https://my-json-server.typicode.com/bintzandt/hydrofielAPI/pages",
			);
			updatePage( result.data[0].nl.replace( "\\", "" ) );
		};
		fetchPage();
	}, [] );
	return (
		<div dangerouslySetInnerHTML={{__html: data}}></div>
	);
};
export default Swimming;
