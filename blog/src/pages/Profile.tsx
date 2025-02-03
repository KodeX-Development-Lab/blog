import { Alert, Avatar, Box, ListItem, ListItemAvatar, ListItemButton, ListItemText, Tab, Tabs, Typography } from "@mui/material";
import { pink } from "@mui/material/colors";
import { fetchUser } from "../libs/fetcher";
import { useParams } from "react-router-dom";
import { useQuery } from "react-query";
import FollowButton from "../components/FollowButton";
import { useState } from "react";
import UserPosts from "../components/UserPosts";

interface TabPanelProps {
	children?: React.ReactNode;
	index: number;
	value: number;
}

function CustomTabPanel(props: TabPanelProps) {
	const { children, value, index, ...other } = props;

	return (
		<div
			role="tabpanel"
			hidden={value !== index}
			id={`simple-tabpanel-${index}`}
			aria-labelledby={`simple-tab-${index}`}
			{...other}
		>
			{value === index && <Box sx={{ p: 3 }}>{children}</Box>}
		</div>
	);
}

function a11yProps(index: number) {
	return {
		id: `simple-tab-${index}`,
		'aria-controls': `simple-tabpanel-${index}`,
	};
}


export default function Profile() {
	const { id } = useParams();
	const [value, setValue] = useState(0);

	const handleChange = (event: React.SyntheticEvent, newValue: number) => {
		setValue(newValue);
	};

	const { isLoading, isError, error, data } = useQuery(
		["user", id],
		async () => fetchUser(id)
	);

	if (isError) {
		return (
			<Box>
				<Alert severity="warning">{error.message}</Alert>
			</Box>
		);
	}

	if (isLoading) {
		return <Box sx={{ textAlign: "center" }}>Loading...</Box>;
	}

	return (
		<Box>
			<Box sx={{ bgcolor: "banner", height: 150, borderRadius: 4 }}></Box>
			<Box
				sx={{
					mb: 4,
					marginTop: "-60px",
					display: "flex",
					flexDirection: "column",
					justifyContent: "center",
					alignItems: "center",
					gap: 1,
				}}>
				<Avatar sx={{ width: 100, height: 100, bgcolor: pink[500] }} />
				<Box sx={{ textAlign: "center" }}>
					<Typography>{data?.data.user.name}</Typography>
					<Typography sx={{ fontSize: "0.8em", color: "text.fade", mb: 3 }}>
						{data?.data.user.bio}
					</Typography>
					<FollowButton user={data?.data.user} />
				</Box>
			</Box>

			<Box sx={{ width: '100%' }}>
				<Box sx={{ borderBottom: 1, borderColor: 'divider' }}>
					<Tabs value={value} onChange={handleChange} aria-label="basic tabs example">
						<Tab label="Followers" {...a11yProps(0)} />
						<Tab label="Followings" {...a11yProps(1)} />
						<Tab label="Posts" {...a11yProps(2)} />
					</Tabs>
				</Box>
				<CustomTabPanel value={value} index={0}>
					{data?.data.user.followers.map(item => {
						return (
							<ListItem key={item.id}>
								<ListItemButton>
									<ListItemAvatar>
										<Avatar />
									</ListItemAvatar>
									<ListItemText
										primary={item.name}

									/>
								</ListItemButton>
							</ListItem>
						);
					})
					}
				</CustomTabPanel>
				<CustomTabPanel value={value} index={1}>
					{data?.data.user.followings.map(item => {
						return (
							<ListItem key={item.id}>
								<ListItemButton>
									<ListItemAvatar>
										<Avatar />
									</ListItemAvatar>
									<ListItemText
										primary={item.name}

									/>
								</ListItemButton>
							</ListItem>
						);
					})
					}
				</CustomTabPanel>
				<CustomTabPanel value={value} index={2}>
					<UserPosts userId={data?.data.user.id} />
				</CustomTabPanel>
			</Box>
		</Box>
	);
}