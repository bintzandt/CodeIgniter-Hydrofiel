import React from "react";

const Footer = () => {
	return (
		<div className="footer">
			<div className="container">
				<div className="agile-footer">
					<div className="agileinfo-social-grids">
						<ul>
							<li>
								<a href="https://nl-nl.facebook.com/Hydrofiel">
									<i className="fab fa-facebook-square" aria-hidden="true" aria-label="facebook" />
								</a>
							</li>
							<li>
								<a href="https://www.snapchat.com/add/hydrofiel">
									<i className="fab fa-snapchat-ghost" aria-hidden="true" />
								</a>
							</li>
							<li>
								<a href="https://www.instagram.com/nszwvhydrofiel/">
									<i className="fab fa-instagram" aria-hidden="true" />
								</a>
							</li>
							<li>
								<a href="mailto:bestuur@hydrofiel.nl">
									<i className="fa fa-envelope" aria-hidden="true" />
								</a>
							</li>
						</ul>
					</div>
				</div>
				<div className="copyright">
					<p>Copyright © {( new Date() ).getFullYear()} N.S.Z.&amp;W.V. Hydrofiel | <a style={{ color: "white" }}
																								 href="https://www.bintzandt.nl">Design&nbsp;by&nbsp;Bram&nbsp;in&nbsp;'t&nbsp;Zandt</a>
					</p>
				</div>
			</div>
		</div>
	);
};

export default Footer;
