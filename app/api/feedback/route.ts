import { feedbackDb } from "@/lib/database"
import { type NextRequest, NextResponse } from "next/server"

export async function POST(request: NextRequest) {
  try {
    const body = await request.json()
    const { name, email, message, rating } = body

    if (!name || !email || !message) {
      return NextResponse.json({ error: "Name, email, and message are required" }, { status: 400 })
    }

    const feedbackId = feedbackDb.createFeedback({ name, email, message, rating })
    return NextResponse.json({ id: feedbackId, message: "Feedback submitted successfully" })
  } catch (error) {
    return NextResponse.json({ error: "Failed to submit feedback" }, { status: 500 })
  }
}
