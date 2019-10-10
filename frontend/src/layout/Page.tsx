import React from "react";

const Page = ( props: { pageName: string } ) => {
	const file = require( "../staticPages/" + props.pageName + ".html" );
	const html = { __html: file };
	return (
		<div dangerouslySetInnerHTML={html}></div>
	);
};

export default Page;
