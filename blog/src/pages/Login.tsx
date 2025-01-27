import { Alert, Box, Button, TextField, Typography } from "@mui/material";

import { useEffect, useRef, useState } from "react";

import { useNavigate } from "react-router-dom";
import { useApp } from "../ThemedApp";
import { postLogin } from "../libs/fetcher";
import { useMutation } from "react-query";

export default function Login() {
	const emailInput = useRef();
	const passwordInput = useRef();

	const [error, setError] = useState<string | null>(null);

	const handleSubmit = () => {
		const email = emailInput.current.value;
		const password = passwordInput.current.value;

		if (!email || !password) {
			setError("email and password required");
			return false;
		}

		login.mutate({ email, password });
	};

	const login = useMutation(
		async ({ email, password }) => postLogin(email, password),
		{
			onError: async () => {
				setError("Incorrect email or password");
			},
			onSuccess: async result => {
				setAuth(result.data.user);
				localStorage.setItem("token", result.data.token);
				navigate("/");
			},
		}
	);

	const navigate = useNavigate();
	const { auth, setAuth } = useApp();

	useEffect(() => {
        if(auth) {
            navigate("/");
        }
    }, [auth]);

	return (
		<Box>
			<Typography variant="h3">Login</Typography>

			{error && (
				<Alert
					severity="warning"
					sx={{ mt: 2 }}>
					{error}
				</Alert>
			)}

			<form
				onSubmit={e => {
					e.preventDefault();
					handleSubmit();
				}}>
				<Box
					sx={{
						display: "flex",
						flexDirection: "column",
						gap: 1,
						mt: 2,
					}}>
					<TextField
						inputRef={emailInput}
						placeholder="Email"
						fullWidth
					/>
					<TextField
						inputRef={passwordInput}
						type="password"
						placeholder="Password"
						fullWidth
					/>
					<Button
						type="submit"
						variant="contained"
						fullWidth>
						Login
					</Button>
				</Box>
			</form>
		</Box>
	);
}