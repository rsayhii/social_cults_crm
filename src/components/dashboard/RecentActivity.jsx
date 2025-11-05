import React from 'react';
// import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
// import { Badge } from "@/components/ui/badge";
// import { format } from 'date-fns';
// import { Clock, User, Megaphone, CheckCircle2 } from 'lucide-react';

export default function RecentActivity({ tasks, clients, campaigns }) {
  const activities = React.useMemo(() => {
    const allActivities = [
      ...tasks.slice(0, 3).map(t => ({
        type: 'task',
        title: t.title,
        date: t.created_date,
        icon: CheckCircle2,
        color: 'text-blue-600',
        bg: 'bg-blue-50'
      })),
      ...clients.slice(0, 2).map(c => ({
        type: 'client',
        title: `New lead: ${c.company_name}`,
        date: c.created_date,
        icon: User,
        color: 'text-green-600',
        bg: 'bg-green-50'
      })),
      ...campaigns.slice(0, 2).map(c => ({
        type: 'campaign',
        title: `Campaign: ${c.name}`,
        date: c.created_date,
        icon: Megaphone,
        color: 'text-purple-600',
        bg: 'bg-purple-50'
      }))
    ].sort((a, b) => new Date(b.date) - new Date(a.date)).slice(0, 6);

    return allActivities;
  }, [tasks, clients, campaigns]);

  return (
    <Card className="border-slate-200/60 bg-white/80 backdrop-blur-sm shadow-lg">
      <CardHeader>
        <CardTitle className="text-lg font-semibold text-slate-900 flex items-center gap-2">
          <Clock className="w-5 h-5" />
          Recent Activity
        </CardTitle>
      </CardHeader>
      <CardContent>
        <div className="space-y-4">
          {activities.map((activity, index) => (
            <div key={index} className="flex items-start gap-3 pb-4 border-b border-slate-100 last:border-0 last:pb-0">
              <div className={`p-2 rounded-lg ${activity.bg}`}>
                <activity.icon className={`w-4 h-4 ${activity.color}`} />
              </div>
              <div className="flex-1">
                <p className="text-sm font-medium text-slate-900">{activity.title}</p>
                <p className="text-xs text-slate-500 mt-1">
                  {format(new Date(activity.date), 'MMM d, yyyy • h:mm a')}
                </p>
              </div>
              <Badge variant="outline" className="capitalize text-xs">
                {activity.type}
              </Badge>
            </div>
          ))}
        </div>
      </CardContent>
    </Card>
  );
}