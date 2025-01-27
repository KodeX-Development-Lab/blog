import { useRef } from "react";
import {
    Box,
    TextField,
    Button,
} from "@mui/material";

export default function Form({ add }: { add: (content: string) => void }) {
    const contentRef = useRef<HTMLInputElement>(null);

    return (
        <form
            onSubmit={e => {
                e.preventDefault();
                const content = contentRef.current.value;
                add(content);
                e.currentTarget.reset();
            }}>
            <Box sx={{ mb: 4, textAlign: "right" }}>
                <TextField
                    inputRef={contentRef}
                    type="text"
                    placeholder="Content"
                    fullWidth
                    multiline
                    sx={{ mb: 1 }}
                />
                <Button
                    variant="contained"
                    type="submit">
                    Post
                </Button>
            </Box>
        </form>
    )
}